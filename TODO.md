# TODO for Payment Pages Implementation

## Steps to Complete:

- [x] Implement PaymentController methods: index (admin/landlord), show, tenantPayments, tenantShow, processPayment.
- [x] Enhance resources/views/landlord/payment.blade.php for listing payments.
- [x] Create resources/views/tenant/payments/index.blade.php for tenant payment listing.
- [x] Create resources/views/tenant/payments/show.blade.php for payment details and pay form.
- [x] Add payment link in resources/views/partials/owner_header.blade.php for landlord.
- [x] Add payment link in resources/views/tenant/homepage.blade.php for tenant.
- [x] Test the implementation: Run server, verify pages, links, and payment processing.

## Additional Changes Made:
- Added transaction_id column to payments table via migration.
- Updated payment flow: Tenant submits transaction_id, status becomes 'pending', Landlord can accept to mark as 'paid'.
- Updated views to display transaction_id instead of method.
- Added acceptPayment method and route for landlords.

## Notes:
- Use simple payment processing (update status to 'paid') without external gateway for now.
- Ensure views use Bootstrap for consistency.
- After each step, update this TODO.md to mark completion.
