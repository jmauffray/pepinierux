import csv
import os
import argparse
import logging
from pathlib import Path
from typing import List

from product import Product, DATE_FORMAT
from duplicate_finder import DuplicateFinder
from product_deleter import ProductDeleter
from xml_downloader import XMLDownloader
from xml_to_csv import process_xml_to_csv

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    datefmt='%Y-%m-%d %H:%M:%S'
)
logger = logging.getLogger(__name__)

# Try to import factuxsql, but don't fail if it's missing or DB is down
try:
    import factuxsql
except ImportError:
    factuxsql = None
    logger.debug("factuxsql module not found, skipping DB checks.")

# --- Configuration ---
DEFAULT_CSV_FILE = Path('incwo_data.csv')
FIELDS_TO_EXTRACT = ['id', 'is_active', 'modified_at', 'name', 'reference']


def load_products_from_csv(file_path: Path) -> List[Product]:
    """Reads products from CSV file and returns a list of Product objects."""
    products = []
    if not file_path.exists():
        logger.error(f"File '{file_path}' not found.")
        return []
    
    try:
        with file_path.open(mode='r', newline='', encoding='utf-8') as csvfile:
            reader = csv.DictReader(csvfile)
            for row in reader:
                products.append(Product.from_dict(row))
        logger.info(f"Loaded {len(products)} products from {file_path}")
        return products
    except Exception as e:
        logger.error(f"Error reading CSV file: {e}")
        return []

def main():
    parser = argparse.ArgumentParser(description="Find and remove duplicate Incwo products.")
    parser.add_argument('--file', type=Path, default=DEFAULT_CSV_FILE, help=f"Input CSV file (default: {DEFAULT_CSV_FILE})")
    parser.add_argument('--dry-run', action='store_true', help="Simulate deletions without executing them")
    
    # Download arguments
    parser.add_argument('--download', action='store_true', help="Download XML files from Incwo")
    parser.add_argument('--pages', type=int, default=100, help="Number of pages to download (default: 100)")
    parser.add_argument('--output-dir', type=str, default="out", help="Directory to save downloaded XML files (default: out)")

    parser.add_argument('--csv', action='store_true', help="Convert XML files from Incwo to CSV")

    args = parser.parse_args()

    # Check Auth
    auth = os.environ.get("INCWO_AUTH")
    if not auth and not args.dry_run:
        logger.warning("INCWO_AUTH environment variable not set. Deletions and downloads will fail.")
    
    if args.download:
        logger.info("Starting download process...")
        downloader = XMLDownloader(auth, args.output_dir, dry_run=args.dry_run)
        downloader.fetch_and_save_data(args.pages)
        return
    
    if args.csv:
        process_xml_to_csv(args.output_dir, args.file, FIELDS_TO_EXTRACT)
        return

    logger.info(f"Reading from {args.file}...")
    products = load_products_from_csv(args.file)
    if not products:
        return

    # Find Duplicates
    finder = DuplicateFinder()
    duplicates = finder.find_duplicates(products)
    
    if duplicates:
        logger.info(f"Identified {len(duplicates)} duplicates.")
        
        # Delete Duplicates
        deleter = ProductDeleter(auth, dry_run=args.dry_run)
        deleter.delete_products(duplicates)
    else:
        logger.info("No duplicates found.")

    # Optional: FactuxSQL check (informational only)
    if factuxsql:
        try:
            logger.info("--- FactuxSQL Check ---")
            factux_ids_removed = factuxsql.get_list_of_ids_removed()
            if factux_ids_removed:
                logger.info(f"Retrieved {len(factux_ids_removed)} IDs removed from local DB factux.")

            deleter = ProductDeleter(auth, dry_run=args.dry_run)
            deleter.delete_products_from_factux(factux_ids_removed, duplicates)
        except Exception as e:
            logger.error(f"FactuxSQL check failed: {e}")

if __name__ == "__main__":
    main()
