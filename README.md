# Risecommerce Auto Related Products Module

The [Magento 2 Auto Related Products](https://risecommerce.com/magento-2-auto-related-products-with-custom-rules.html) extension enhances your store by automatically displaying related, cross-sell, and up-sell products based on customizable rules. These rules can be applied to product, category, shopping cart, and checkout pages, making it easier for customers to discover products they need.

For more details about the extension, visit the [Risecommerce Auto Related Products page](https://risecommerce.com/magento-2-auto-related-products-with-custom-rules.html).

If you're looking to enhance your Magento store further, consider hiring a [dedicated Magento developer](https://risecommerce.com/hire-dedicated-magento-developer.html).

For support or inquiries, please visit our [contact page](https://risecommerce.com/contact).

## Supported Versions
- Magento 2.3.x
- Magento 2.4.x

## Installation Instructions

### Method I: Manual Installation

1. Download the archive file.
2. Unzip the file.
3. Create the following directory: `[Magento_Root]/app/code/Risecommerce/AutoRelatedProducts`.
4. Move the unzipped files into the directory `[Magento_Root]/app/code/Risecommerce/AutoRelatedProducts`.

### Method II: Installation Using Composer

composer require risecommerce/magento-2-auto-related-products-extension:1.0.1

Run the following command:

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


