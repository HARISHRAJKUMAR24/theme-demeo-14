<?php if (isset($_GET['step'])) :

    $userAuthClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider";
    $checkoutClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider";
    $successClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none border-gray-200 hover:text-gray-900 tracking-wider";

    if ($page === "login.php" || $page === "register.php") {
        $userAuthClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium bg-gray-100 items-center leading-none border-primary-500 text-primary-500 tracking-wider rounded-t";
    }
    if ($page === "checkout.php") {
        $checkoutClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium bg-gray-100 items-center leading-none border-primary-500 text-primary-500 tracking-wider rounded-t";
        $userAuthClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none bg-green-100 text-green-500 border-b-2 border-green-500 tracking-wider";
    }
    if ($page === "success.php") {
        $successClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium bg-gray-100 items-center leading-none border-primary-500 text-primary-500 tracking-wider rounded-t";
        $userAuthClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none bg-green-100 text-green-500 border-b-2 border-green-500 tracking-wider";
        $checkoutClass = "sm:px-6 py-3 lg:w-1/3 sm:w-auto flex justify-center border-b-2 title-font font-medium items-center leading-none bg-green-100 text-green-500 border-b-2 border-green-500 tracking-wider";
    }

?>
<?php endif; ?>