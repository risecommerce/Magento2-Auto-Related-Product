#Risecommerce Auto Related Products Module

Magento 2 Auto Related Products is an extension that uses sets of conditions and actions as rules to dynamically showcase related, cross-sell, and up-sell items in your store to help customers easily find necessary products on the product, category, shopping cart, and checkout pages.

##Support: 
version - 2.3.x, 2.4.x

##How to install Extension

1. Download the archive file.
2. Unzip the file
3. Create a folder [Magento_Root]/app/code/Risecommerce/AutoRelatedProducts
4. Drop/move the unzipped files to directory '[Magento_Root]/app/code/Risecommerce/AutoRelatedProducts'

#Enable Extension:
- php bin/magento module:enable Risecommerce_AutoRelatedProducts
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush

#Disable Extension:
- php bin/magento module:disable Risecommerce_AutoRelatedProducts
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile
- php bin/magento setup:static-content:deploy
- php bin/magento cache:flush