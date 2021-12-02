<?php

include "AppData.php";

include "Models/Shop.php";
include "Models/Order.php";

include "Repositories/ShopRepository.php";
include "Repositories/OrderRepository.php";

include "Services/SignatureValidator.php";
include "Services/Mailer.php";


include "Helper/framework/Services/Logger.php";

include "Helper/framework/Services/Request.php";
include "Helper/framework/Services/Response.php";
include "Helper/framework/Services/HttpClient.php";
include "Helper/framework/Services/RandomString.php";

include "vendor/php-smtp/src/Email.php";