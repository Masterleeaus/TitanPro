ROLLBACK — Titan Leads v1.4

- Remove the TitanLeads folder changes by restoring the previous extension ZIP (v1.3)
- If migrations were run, you can rollback recent migrations:
cd /home/saassmar/domains/buildsm.art/public_html && php artisan migrate:rollback --step=3
(Adjust step count based on how many migrations executed.)
