CREATE TABLE users (
  id int(11) NOT NULL,
  phone varchar(10) NOT NULL,
  salesphone varchar(10) NOT NULL,
  adminphone varchar(10) NOT NULL,
  firstname varchar(50) NOT NULL,
  lastname varchar(50) NOT NULL,
  subdistrict varchar(50) NOT NULL,
  district varchar(50) NOT NULL,
  address varchar(100) NOT NULL,
  province varchar(50) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


CREATE TABLE kiosks (
  id int(11) NOT NULL,
  kiosk_account varchar(15) DEFAULT NULL,
  owner_phone varchar(15) DEFAULT NULL,
  sales_phone varchar(15) DEFAULT NULL,
  admin_phone varchar(15) DEFAULT NULL,
  kiosk_code varchar(50) DEFAULT NULL,
  owner_name varchar(100) DEFAULT NULL,
  owner_address text DEFAULT NULL,
  sales_name varchar(100) DEFAULT NULL,
  kiosk_price decimal(10,0) DEFAULT NULL,
  down_payment decimal(10,0) DEFAULT NULL,
  payment_method enum('fixed','percentage') DEFAULT NULL,
  monthly_fixed_payment decimal(10,0) DEFAULT NULL,
  monthly_percentage_payment decimal(5,0) DEFAULT NULL,
  installment_months int(11) DEFAULT NULL,
  kiosk_time timestamp NOT NULL DEFAULT current_timestamp(),
  installment_zero_percent_months int(11) DEFAULT 3,
  installment_interest_percent decimal(5,2) DEFAULT 2.50
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE installments (
  id int(11) NOT NULL,
  kiosk_id int(11) NOT NULL,
  kiosk_number varchar(50) NOT NULL,
  owner_name varchar(255) NOT NULL,
  kiosk_account varchar(15) DEFAULT NULL,
  owner_phone varchar(20) NOT NULL,
  month_date text NOT NULL,
  installment_no int(11) NOT NULL,
  status varchar(50) NOT NULL,
  remaining_amount decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;