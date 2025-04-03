<?php

# The file is needed to allow redirects from 3rd party sites to the Merchant Portal. The default session cookie settings is SameSite=Strict and the browser does not allow to read session cookies.
# Using intermediate page on the Merchant Portal domain, the session cookies are visible and a user can continue with his session.
if (empty($_GET['url'])) {
    exit('No redirect URL provided');
}

$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);

if (empty($url)) {
    exit('Redirect URL invalid');
}

$referrer = !empty($_GET['HTTP_REFERER'])
    ? filter_var($_GET['HTTP_REFERER'], FILTER_SANITIZE_URL)
    : '';

?>
<p style="text-align: center">
    The site <?php echo $referrer; ?> wants to redirect you to <strong><?php echo $url ?></strong>. Do you want to
    follow the redirect?
</p>
<p style="text-align: center">
    <a href="<?php echo $url; ?>">Follow</a>
</p>
