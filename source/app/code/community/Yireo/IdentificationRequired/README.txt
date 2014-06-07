Steps to get this extension working:

Install the extension:
Go to "CMS > Page" and create a new page for your agreement
Go to "System > Configuration > Catalog > Identification Required
- Configure for which category IDs or product IDs you want to require identification
- Configure the CMS-page under "Agreement page"
- Modify the text of all notices as you see fit

Where can you see this extension in action?
- Product-page: Notice close to addtocart-button
- Checkout-page: Notice above content
- Cart-page: Notice above content
- Extra intermediate page between cart and checkout
- Customer account: Extra tab "My Identification"
- Backend: Extra tab "Identification Required" when editing a customer

How it works?
- When a product, for which identification is required, is added to cart, notices are shown to customer
- Also an intermediate page between cart and checkout (precheckout) is shown
- Order is accepted as normal, but items are stored with flag "locked_do_ship" set to 1
- Customer is asked to complete information under "My Identification"
- Admin is able to fill in these details also under the customer-edit page in the Admin Panel
- Admin is required to set identification-status to "Accepted" once manual checks are complete
- If admin tries to ship the order, Magento generates message "Cannot do shipment for the order"
- Order can only be shipped when identification-status is set to "Accepted"
- When identification-status is set to "Accepted", customer will not see any notices anymore when ordering
