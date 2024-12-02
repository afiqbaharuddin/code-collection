<?php

//generate hash password
// $pass = password_hash("synevent123", PASSWORD_BCRYPT);

// $hash = '$2y$10$.vGA1O9wmRjrwAVXD98HNOgsNpDczlqm3Jq7KnEd1rVAGv3Fykk1a';

$pass = '$2y$10$1pysgmU4IhdenpHr7YnLuuwZdfh/9BIdtuzQmKzyc1DtW/cEdTezS';


if (password_verify('synevent123', $pass)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}