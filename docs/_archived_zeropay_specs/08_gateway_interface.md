
# Gateway Interface Specification

All gateways must implement:

createPayment(session)
verifyPayment(reference)
handleWebhook(payload)
calculateFee(amount)
refundPayment(transaction_id)

Supported gateways:

PayID
BankTransfer
Cash
Stripe
PayPal
Cryptomus
