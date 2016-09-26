# update graphics to 61701
UPDATE products,order_type
SET products.expense_category = "61701"
WHERE products.prod_type_id = order_type.id AND order_type.order_type = "Graphics"

# update office supplies to 61603
UPDATE products,order_type
SET products.expense_category = "61603"
WHERE products.prod_type_id = order_type.id AND order_type.order_type = "Office Supplies"

# update party supplies to 61605
UPDATE products,order_type
SET products.expense_category = "61605"
WHERE products.prod_type_id = order_type.id AND order_type.order_type = "Party Supplies"

# update office supplies to 61703
UPDATE products,order_type
SET products.expense_category = "61703"
WHERE products.prod_type_id = order_type.id AND order_type.order_type = "Redemption Prizes"

# update office supplies to 61605
UPDATE products,order_type
SET products.expense_category = "61605"
WHERE products.prod_type_id = order_type.id AND order_type.order_type = "Party Supplies"
