create table user
(
    id         int auto_increment
        primary key,
    full_name  varchar(255) null,
    first_name varchar(255) null,
    name       varchar(255) null,
    gender     varchar(255) null,
    email      varchar(255) null,
    phone      varchar(255) null
) engine = InnoDB;

create table product
(
    id            int auto_increment
        primary key,
    name          varchar(255) null,
    cost_price    float        null,
    selling_price float        null,
    category      varchar(255) null
)
    engine = InnoDB;



create table `order`
(
	id int auto_increment,
	date datetime null,
	user int null,
	constraint order_pk
		primary key (id),
	constraint order_user_id_fk
		foreign key (user) references user (id)
) ENGINE = InnoDB;

create table order_line
(
	id int auto_increment,
	`order` int,
	product int,
	quantity float null,
	sold_price_vat_excluded float null,
	vat float null,
	sold_price_vat_included float null,
	total float null,
	constraint order_line_pk
		primary key (id),
	constraint order_line_order_id_fk
		foreign key (`order`) references `order` (id),
  constraint order_line_product_id_fk
		foreign key (product) references product (id)
) ENGINE = InnoDB;

