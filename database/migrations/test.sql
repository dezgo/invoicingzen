-- mysql -u root -p invoicingzen < database/migrations/test.sql
select
    i.*,
    sum(ii.quantity * ii.price) as total
from invoices as i
    inner join invoice_items as ii on i.id = ii.invoice_id
    inner join users as u on u.id = i.customer_id
where
    u.company_id = 1 and
    not i.is_quote
group by
    i.id
having
    total = i.paid
