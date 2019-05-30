# Use case: Age verification
Within the extension, you can create a rule to verify the age of a customer. Whenever a customer buys a product that requires a certain legal age, the customer will be able to enter his or her date of birth in the account settings. Unfortunately, simply entering a date does not mean its verified. Manual confirmation is needed.

Our extension allows for legal documents (a passport, drives license or some other ID) to be uploaded to the customers account. Within the backend, you can then inspect the customers documents and complete the verification process.

The rule can be tuned to allow ordering products, but not shipping the products unless the verification has been completed. Or you can deny the checkout process alltogether. You get the flexibility to build the process you need.

# Use case: Country / IP check
Within the EU, businesses can make transactions without paying tax by applying their VAT ID - this is possible in the Magento core by default. However, EU consumers - non-business - need to pay tax. When you are selling digital goods, the EU requires you to apply the tax from your customers home country, not your own. Additionally, the EU requires you to verify that the customers home country is entered correctly. Our Identification Required extension allows you to comply to these rules.

In the Magento backend, you can create rules of type Country / IP check. This check allows you to check whether the country the customer has entered matches the IP address the customer is visiting from. If the check fails, so if the IP and country do not match, further transactions can be denied.
