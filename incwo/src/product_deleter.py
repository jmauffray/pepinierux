import requests
import logging
from typing import List, Optional
from product import Product
import config

logger = logging.getLogger(__name__)

INCWO_API_URL = config.url

class ProductDeleter:
    """Handles the deletion of products via the Incwo API."""

    def __init__(self, auth: Optional[str], dry_run: bool = False):
        self.dry_run = dry_run
        self.username = None
        self.password = None
        
        if auth:
            if ':' in auth:
                self.username, self.password = auth.split(':', 1)
            else:
                logger.error("Error: INCWO_AUTH must be in format username:password")
        elif not dry_run:
             logger.warning("No authentication provided. Deletions will likely fail.")

    def delete_products(self, products_to_delete: List[Product]) -> None:
        """Deletes the specified products."""
        if not products_to_delete:
            logger.info("No products to delete.")
            return

        logger.info(f"Found {len(products_to_delete)} products to delete.")
        
        if self.dry_run:
            logger.info("DRY RUN MODE: No deletions will be performed.")

        success_count = 0
        fail_count = 0

        for p in products_to_delete:
            if self._delete_single_product(p):
                success_count += 1
            else:
                fail_count += 1
        
        if not self.dry_run:
            logger.info(f"Deletion complete. Success: {success_count}, Failed: {fail_count}")

    def delete_products_from_factux(self, factux_products_to_delete: List[str],
                                    incwo_products: List[Product]) -> None:
        """Deletes the specified products."""
        if not factux_products_to_delete:
            logger.info("No products to delete.")
            return

        logger.info(f"Found {len(factux_products_to_delete)} products to delete.")
        
        if self.dry_run:
            logger.info("DRY RUN MODE: No deletions will be performed.")

        success_count = 0
        fail_count = 0

        for p in incwo_products:
            #print(p.id)
            if int(p.id) in factux_products_to_delete:
                if self._delete_single_product(p):
                    success_count += 1
                else:
                    fail_count += 1
        
        if not self.dry_run:
            logger.info(f"Deletion complete. Success: {success_count}, Failed: {fail_count}")

    def _delete_single_product(self, product: Product) -> bool:
        """Deletes a single product. Returns True if successful."""
        del_url = f"{INCWO_API_URL}{product.id}.xml"
        msg = f"DELETE {del_url} (Ref: {product.reference})"
        
        if self.dry_run:
            logger.info(f"[DRY RUN] {msg}")
            return True

        logger.info(msg)
        
        if not self.username or not self.password:
            return False

        try:
            #res = requests.delete(del_url, auth=(self.username, self.password), timeout=10)
            #res = requests.put(del_url, data="<is_active>0</is_active>", auth=(self.username, self.password), timeout=10)
            if res.status_code in [200, 204]:
                logger.info(f"  ✅ Deleted {product.id}")
                return True
            else:
                logger.error(f"  ❌ Failed {product.id}: {res.status_code} {res.text}")
                return False
        except requests.RequestException as e:
            logger.error(f"  ❌ Network Error {product.id}: {e}")
            return False
        except Exception as e:
            logger.error(f"  ❌ Error {product.id}: {e}")
            return False
