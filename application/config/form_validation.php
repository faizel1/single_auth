<?php

$config = [

    #region Application

    #region  Application Consumer
    "Application/consumer" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],

        [
            "field" => "password",
            "label" => "Password",
            "rules" => "required",
        ],
        [
            "field" => "phone_number",
            "label" => "Phone Number",
            "rules" => "required",
        ],

        [
            "field" => "full_name",
            "label" => "Full Name",
            "rules" => "required",
        ],
    ],
    #endregion
    #region  Application Supplier

    "Application/supplier" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],
        [
            "field" => "phone_number",
            "label" => "Phone Number",
            "rules" => "required",
        ],
        [
            "field" => "company_name",
            "label" => "Company Name",
            "rules" => "required",
        ],
        "address" => [
            [
                "field" => "region",
                "label" => "region",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "city",
                "label" => "city",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "sub_city",
                "label" => "sub_city",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "wereda",
                "label" => "wereda",
                "rules" => "required|max_length[20]",
            ],
            [
                "field" => "email_address",
                "label" => "email_address",
                "rules" => "required|valid_email|max_length[100]|callback_is_exist[email_address]",
            ],
            [
                "field" => "phone_number",
                "label" => "phone_number",
                "rules" => "required|max_length[20]|callback_is_exist[phone_number]",
            ]
        ],

        "contact_person_address" => [

            [
                "field" => "email_address",
                "label" => "email_address",
                "rules" => "required|valid_email|max_length[100]|callback_is_exist[email_address]",
            ],
            [
                "field" => "phone_number",
                "label" => "phone_number",
                "rules" => "required|max_length[20]|callback_is_exist[phone_number]",
            ]
        ],
        "areas" => []


    ],
    #endregion 
    #region Application Driver

    "Application/driver" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],
        [
            "field" => "phone_number",
            "label" => "Phone Number",
            "rules" => "required",
        ],
        [
            "field" => "full_name",
            "label" => "Full Name",
            "rules" => "required",
        ],
        "address" => [
            [
                "field" => "region",
                "label" => "region",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "city",
                "label" => "city",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "sub_city",
                "label" => "sub_city",
                "rules" => "required|max_length[100]",
            ],
            [
                "field" => "wereda",
                "label" => "wereda",
                "rules" => "required|max_length[20]",
            ],
            [
                "field" => "email_address",
                "label" => "email_address",
                "rules" => "required|valid_email|max_length[100]|callback_is_exist[email_address]",
            ],
            [
                "field" => "phone_number",
                "label" => "phone_number",
                "rules" => "required|max_length[20]|callback_is_exist[phone_number]",
            ]
        ],

        "vehicle_info" => [
            [
                "field" => "vehicle_type",
                "label" => "vehicle_type",
                "rules" => "required|valid_email|max_length[100]|callback_is_exist[email_address]",
            ],
            [
                "field" => "plate_number",
                "label" => "plate_number",
                "rules" => "required|valid_email|max_length[100]|callback_is_exist[email_address]",
            ],

        ]

    ],
    #endregion

    #endregion

    #region Authentication


    "Authentication/find" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],
    ],
    "Authentication/check_pin" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],

        [
            "field" => "pin",
            "label" => "Pin Code",
            "rules" => "required",
        ],
    ],
    "Authentication/change_password" => [
        [
            "field" => "id",
            "label" => "Id",
            "rules" => "required",
        ],
        [
            "field" => "old_password",
            "label" => "Old Password",
            "rules" => "required",
        ],

        [
            "field" => "new_password",
            "label" => "New Password",
            "rules" => "required",
        ],
    ],
    "Authentication/login" => [
        [
            "field" => "email",
            "label" => "Email Address",
            "rules" => "required",
        ],

        [
            "field" => "password",
            "label" => "Password",
            "rules" => "required",
        ],
    ],    "Authentication/recover_password" => [
        [
            "field" => "id",
            "label" => "Id",
            "rules" => "required",
        ],
        [
            "field" => "password",
            "label" => "Password",
            "rules" => "required",
        ],

    ]

    #endregion

    #region User 
    , "User/update_profile" => [
        [
            "field" => "id",
            "label" => "Id",
            "rules" => "required",
        ],

    ],
    "User/add_address" => [

        [
            "field" => "id",
            "label" => "Id",
            "rules" => "required",
        ]


    ],
    "User/update_address" => [
        [
            "field" => "id", "label" => "id",             "rules" => "required",
        ],
    ]
    #endregion

    #region Order 

    , "Order/place_order" => [

        "order_detail" =>   [],
        [
            "field" => "shipping_address_id", "label" => "shipping_address_id",             "rules" => "required",
        ],
        [
            "field" => "payment_method", "label" => "payment_method",             "rules" => "required",
        ],
        [
            "field" => "client_id", "label" => "client_id",             "rules" => "required",
        ],
        [
            "field" => "supplier_id", "label" => "supplier_id",             "rules" => "required",
        ]
    ],


    #endregion
    #region Util
    "Util/contact_us" => [
        ["field" => "email_address", "label" => "email_address", "rules" => "required"],
        ["field" => "phone_number", "label" => "phone_number", "rules" => "required"],
        ["field" => "complain", "label" => "complain", "rules" => "required"],
        ["field" => "complain_by", "label" => "complain_by", "rules" => "required"],
    ],
    "Util/lookup_list" => [
        ["field" => "parent_id", "label" => "parent_id", "rules" => "required"],
        ["field" => "lookup_type", "label" => "lookup_type", "rules" => "required"],

    ],

    #endregion

    #region Product
    "Product/post_review" => [
        ["field" => "review_comment", "label" => "review_comment", "rules" => "required"],
        ["field" => "review_by", "label" => "review_by", "rules" => "required"],
        ["field" => "rate", "label" => "rate", "rules" => "required"],
        ["field" => "product_id", "label" => "product_id", "rules" => "required"],
    ],


    #endregion

    #region Payment
    "Payment/create" => [
        ["field" => "bank_name", "label" => "bank_name", "rules" => "required"],
        ["field" => "account_no", "label" => "account_no", "rules" => "required"],
        ["field" => "user_id", "label" => "rate", "user_id" => "required"],
        ["field" => "branch", "label" => "branch", "user_id" => "required"],
    ],
    "Payment/update" => [
        ["field" => "bank_name", "label" => "bank_name", "rules" => "required"],
        ["field" => "account_no", "label" => "account_no", "rules" => "required"],
        ["field" => "user_id", "label" => "rate", "user_id" => "required"],
        ["field" => "branch", "label" => "branch", "user_id" => "required"],
    ],
    "Payment/delete" => [
        ["field" => "id", "label" => "id", "rules" => "required"],
    ],

    #endregion

    #region Product
    "Home/search" => [
        ["field" => "search_query", "label" => "Search Query", "rules" => "required"],
        ["field" => "type", "label" => "type", "rules" => "required"],
    ],

    #endregion

];
