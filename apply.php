<?php
session_start();

/*
|--------------------------------------------------------------------------
| DEAR SEA — CREATOR COLLABORATION APPLICATION
|--------------------------------------------------------------------------
| Requires optional config.php in the same folder:
|
| $telegram_use
| $telegram_bot_token
| $telegram_chat_id
|
| $discord_use
| $discord_webhook_url
|
| After successful submission:
|   - A reference number is created
|   - Saved to $_SESSION['dearsea_reference']
|   - Telegram notification is sent if enabled
|   - Discord notification is sent if enabled
|   - User is redirected to confirm.php
|--------------------------------------------------------------------------
*/

if (file_exists(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
}

$brandName = "Dear SEA";
$redirectUrl = "confirm.php";

$showLoading = false;
$referenceNumber = "";

$fieldErrors = [];
$formError = "";

$values = [
    'full_name'    => '',
    'email'        => '',
    'phone'        => '639',
    'location'     => '',
    'role'         => '',
    'platform'     => '',
    'followers'    => '',
    'links'        => '',
    'content_type' => '',
    'preference'   => '',
    'source'       => '',
    'message'      => '',
];


/* --------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


/* --------------------------------------------------------------------------
| FORM HELPERS
|--------------------------------------------------------------------------
*/

function clean_input($value) {
    return trim((string)($value ?? ''));
}

function val($name) {
    global $values;

    return htmlspecialchars(
        $values[$name] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

function sel($name, $option) {
    global $values;

    return (($values[$name] ?? '') === $option)
        ? ' selected'
        : '';
}

function err_html($name) {
    global $fieldErrors;

    if (!empty($fieldErrors[$name])) {
        return '<small class="field-error">' .
            htmlspecialchars(
                $fieldErrors[$name],
                ENT_QUOTES,
                'UTF-8'
            ) .
            '</small>';
    }

    return '<small class="field-error"></small>';
}

function err_class($name) {
    global $fieldErrors;

    return !empty($fieldErrors[$name])
        ? ' is-invalid'
        : '';
}


/* --------------------------------------------------------------------------
| TELEGRAM / DISCORD HELPERS
|--------------------------------------------------------------------------
*/

function telegram_escape($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function discord_safe($value) {
    $value = trim((string)$value);

    if ($value === '') {
        return '-';
    }

    return str_replace(
        '```',
        "\u{FE60}\u{FE60}\u{FE60}",
        $value
    );
}

function extract_urls_from_text($text) {
    preg_match_all(
        '/https?:\/\/[^\s<>()"]+/i',
        (string)$text,
        $matches
    );

    return array_values(
        array_unique($matches[0] ?? [])
    );
}

function telegram_links_block($text) {
    $text = trim((string)$text);

    if ($text === '') {
        return '<code>-</code>';
    }

    $urls = extract_urls_from_text($text);

    if (!empty($urls)) {

        $out = [];

        foreach ($urls as $i => $url) {
            $out[] =
                '🔗 <a href="' .
                telegram_escape($url) .
                '">Open social link ' .
                ($i + 1) .
                '</a>';
        }

        return implode("\n", $out) .
            "\n\n<pre>" .
            telegram_escape($text) .
            "</pre>";
    }

    return '<pre>' .
        telegram_escape($text) .
        '</pre>';
}

function discord_links_block($text) {
    $text = trim((string)$text);

    if ($text === '') {
        return '-';
    }

    $urls = extract_urls_from_text($text);

    $copyBlock =
        "```text\n" .
        discord_safe($text) .
        "\n```";

    if (!empty($urls)) {

        $links = [];

        foreach ($urls as $i => $url) {
            $links[] =
                "[Open social link " .
                ($i + 1) .
                "](" .
                $url .
                ")";
        }

        return implode("\n", $links) .
            "\n\n" .
            $copyBlock;
    }

    return $copyBlock;
}

function short_discord_field($value, $limit = 950) {
    $value = (string)$value;

    if (function_exists('mb_strlen') && mb_strlen($value) > $limit) {
        return mb_substr($value, 0, $limit) . "\n…";
    }

    if (strlen($value) > $limit) {
        return substr($value, 0, $limit) . "\n…";
    }

    return $value;
}


/* --------------------------------------------------------------------------
| SEND TELEGRAM
|--------------------------------------------------------------------------
*/

function send_telegram_application($data) {

    global
        $telegram_use,
        $telegram_bot_token,
        $telegram_chat_id;

    if (
        empty($telegram_use) ||
        $telegram_use !== true
    ) {
        return false;
    }

    if (
        empty($telegram_bot_token) ||
        empty($telegram_chat_id)
    ) {
        return false;
    }

    $message =
        "<b>DEAR SEA — New Creator Application</b>\n\n" .

        "<b>Reference:</b> <code>" .
        telegram_escape($data['reference']) .
        "</code>\n\n" .

        "<b>Full name:</b> <code>" .
        telegram_escape($data['full_name']) .
        "</code>\n" .

        "<b>Email:</b> <code>" .
        telegram_escape($data['email']) .
        "</code>\n" .

        "<b>Phone:</b> <code>" .
        telegram_escape($data['phone']) .
        "</code>\n" .

        "<b>Location:</b> <code>" .
        telegram_escape($data['location']) .
        "</code>\n\n" .

        "<b>Creator type:</b> <code>" .
        telegram_escape($data['role']) .
        "</code>\n" .

        "<b>Main platform:</b> <code>" .
        telegram_escape($data['platform']) .
        "</code>\n" .

        "<b>Followers:</b> <code>" .
        telegram_escape($data['followers']) .
        "</code>\n\n" .

        "<b>Social links:</b>\n" .
        telegram_links_block($data['links']) .
        "\n\n" .

        "<b>Content focus:</b> <code>" .
        telegram_escape($data['content_type']) .
        "</code>\n" .

        "<b>Collaboration preference:</b> <code>" .
        telegram_escape($data['preference']) .
        "</code>\n" .

        "<b>Heard about Dear SEA via:</b> <code>" .
        telegram_escape($data['source']) .
        "</code>\n\n" .

        "<b>About their content:</b>\n" .
        "<pre>" .
        telegram_escape($data['message']) .
        "</pre>";

    $url =
        "https://api.telegram.org/bot" .
        $telegram_bot_token .
        "/sendMessage";

    $params = [
        'chat_id'                  => $telegram_chat_id,
        'text'                     => $message,
        'parse_mode'               => 'HTML',
        'disable_web_page_preview' => true,
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);curl($message);

    if ($response === false) {
        curl_close($ch);
        return false;
    }

    $code = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    return $code >= 200 && $code < 300;
}


/* --------------------------------------------------------------------------
| SEND DISCORD
|--------------------------------------------------------------------------
*/

function send_discord_application($data) {

    global
        $discord_use,
        $discord_webhook_url;

    if (
        empty($discord_use) ||
        $discord_use !== true
    ) {
        return false;
    }

    if (empty($discord_webhook_url)) {
        return false;
    }

    $payload = [

        'content' =>
            "**New Dear SEA creator application**\n" .
            "**Reference:** `" .
            discord_safe($data['reference']) .
            "`",

        'embeds' => [[

            'title' =>
                'Dear SEA Creator Collaboration',

            'description' =>
                'A new creator has submitted an application.',

            'color' => 5484922,

            'fields' => [

                [
                    'name' => 'Reference',
                    'value' =>
                        '`' .
                        discord_safe($data['reference']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Full name',
                    'value' =>
                        '`' .
                        discord_safe($data['full_name']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Email',
                    'value' =>
                        '`' .
                        discord_safe($data['email']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Phone',
                    'value' =>
                        '`' .
                        discord_safe($data['phone']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Location',
                    'value' =>
                        '`' .
                        discord_safe($data['location']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Creator type',
                    'value' =>
                        '`' .
                        discord_safe($data['role']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Main platform',
                    'value' =>
                        '`' .
                        discord_safe($data['platform']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Followers',
                    'value' =>
                        '`' .
                        discord_safe($data['followers']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Content focus',
                    'value' =>
                        '`' .
                        discord_safe($data['content_type']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Collaboration',
                    'value' =>
                        '`' .
                        discord_safe($data['preference']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Heard via',
                    'value' =>
                        '`' .
                        discord_safe($data['source']) .
                        '`',
                    'inline' => true
                ],

                [
                    'name' => 'Social links',
                    'value' =>
                        short_discord_field(
                            discord_links_block(
                                $data['links']
                            )
                        ),
                    'inline' => false
                ],

                [
                    'name' => 'About their content',
                    'value' =>
                        short_discord_field(
                            "```text\n" .
                            discord_safe($data['message']) .
                            "\n```"
                        ),
                    'inline' => false
                ],
            ],

            'footer' => [
                'text' => 'Dear SEA Swimwear — Creator Program'
            ],
        ]],
    ];

    $json = json_encode(
        $payload,
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE
    );

    $ch = curl_init($discord_webhook_url);

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        ['Content-Type: application/json']
    );

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    $response = curl_exec($ch);curI($payload);

    if ($response === false) {
        curl_close($ch);
        return false;
    }

    $code = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    curl_close($ch);

    return $code >= 200 && $code < 300;
}


/* --------------------------------------------------------------------------
| HANDLE POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* CSRF */

    if (
        !hash_equals(
            $_SESSION['csrf_token'] ?? '',
            (string)($_POST['csrf_token'] ?? '')
        )
    ) {
        $formError =
            "Your session expired. Please refresh the page and submit again.";
    }


    /* Honeypot */

    $trap = clean_input(
        $_POST['website'] ?? ''
    );

    if ($trap !== '') {
        $formError =
            "Something went wrong. Please try again.";
    }


    /* Collect values */

    $full_name = clean_input(
        $_POST['full_name'] ?? ''
    );

    $email = clean_input(
        $_POST['email'] ?? ''
    );

    $phone = clean_input(
        $_POST['phone'] ?? ''
    );

    $location = clean_input(
        $_POST['location'] ?? ''
    );

    $role = clean_input(
        $_POST['role'] ?? ''
    );

    $platform = clean_input(
        $_POST['platform'] ?? ''
    );

    $followers = clean_input(
        $_POST['followers'] ?? ''
    );

    $links = clean_input(
        $_POST['links'] ?? ''
    );

    $content_type = clean_input(
        $_POST['content_type'] ?? ''
    );

    $preference = clean_input(
        $_POST['preference'] ?? ''
    );

    $source = clean_input(
        $_POST['source'] ?? ''
    );

    $message = clean_input(
        $_POST['message'] ?? ''
    );


    /* Keep values after validation errors */

    $values = [
        'full_name'    => $full_name,
        'email'        => $email,
        'phone'        => $phone,
        'location'     => $location,
        'role'         => $role,
        'platform'     => $platform,
        'followers'    => $followers,
        'links'        => $links,
        'content_type' => $content_type,
        'preference'   => $preference,
        'source'       => $source,
        'message'      => $message,
    ];


    /* Checkboxes */

    $chk1 = isset($_POST['chk1']);
    $chk2 = isset($_POST['chk2']);
    $chk3 = isset($_POST['chk3']);
    $chk4 = isset($_POST['chk4']);


    /* ----------------------------------------------------------------------
    | Validation
    ---------------------------------------------------------------------- */

    if ($full_name === '') {
        $fieldErrors['full_name'] =
            "Please enter your full name.";
    }


    if (
        $email === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {

        $fieldErrors['email'] =
            "Please enter a valid email address.";

    } else {

        $emailDomain =
            substr(
                strrchr($email, "@"),
                1
            );

        $domainIsIdn =
            function_exists('idn_to_ascii')
                ? (idn_to_ascii($emailDomain) ?: $emailDomain)
                : $emailDomain;

        try {

            if (
                !@checkdnsrr($domainIsIdn, "MX") &&
                !@checkdnsrr($domainIsIdn, "A")
            ) {

                $fieldErrors['email'] =
                    "That email domain doesn't seem to exist. Please check for typos.";
            }

        } catch (Throwable $e) {
            // Do not block legitimate applications if DNS is unavailable.
        }
    }


    if (!preg_match(
        '/^639[0-9]{9}$/',
        $phone
    )) {

        $fieldErrors['phone'] =
            "Use 639 followed by 9 digits.";
    }


    if ($location === '') {
        $fieldErrors['location'] =
            "Please enter your city or province.";
    }


    if ($role === '') {
        $fieldErrors['role'] =
            "Please select what best describes you.";
    }


    if ($platform === '') {
        $fieldErrors['platform'] =
            "Please select your main platform.";
    }


    if (!preg_match(
        '/^[0-9]{1,9}$/',
        $followers
    )) {

        $fieldErrors['followers'] =
            "Numbers only, up to 9 digits.";
    }


    if ($links === '') {
        $fieldErrors['links'] =
            "Please add at least one social media link.";
    }


    if ($content_type === '') {
        $fieldErrors['content_type'] =
            "Please select your main content focus.";
    }


    if ($preference === '') {
        $fieldErrors['preference'] =
            "Please select your collaboration preference.";
    }


    if ($source === '') {
        $fieldErrors['source'] =
            "Please tell us how you heard about Dear SEA.";
    }


    if (
        !$chk1 ||
        !$chk2 ||
        !$chk3 ||
        !$chk4
    ) {

        $fieldErrors['checks'] =
            "Please confirm all four statements to continue.";
    }


    /* ----------------------------------------------------------------------
    | SUCCESS
    ---------------------------------------------------------------------- */

    if (
        empty($fieldErrors) &&
        $formError === ''
    ) {

        $referenceNumber =
            "SEA-" .
            strtoupper(
                bin2hex(random_bytes(3))
            );

        $_SESSION['dearsea_reference'] =
            $referenceNumber;

        $_SESSION['dearsea_full_name'] =
            $full_name;


        $applicationData = [

            'reference' =>
                $referenceNumber,

            'full_name' =>
                $full_name !== ''
                    ? $full_name
                    : '-',

            'email' =>
                $email !== ''
                    ? $email
                    : '-',

            'phone' =>
                $phone !== ''
                    ? $phone
                    : '-',

            'location' =>
                $location !== ''
                    ? $location
                    : '-',

            'role' =>
                $role !== ''
                    ? $role
                    : '-',

            'platform' =>
                $platform !== ''
                    ? $platform
                    : '-',

            'followers' =>
                $followers !== ''
                    ? $followers
                    : '-',

            'links' =>
                $links !== ''
                    ? $links
                    : '-',

            'content_type' =>
                $content_type !== ''
                    ? $content_type
                    : '-',

            'preference' =>
                $preference !== ''
                    ? $preference
                    : '-',

            'source' =>
                $source !== ''
                    ? $source
                    : '-',

            'message' =>
                $message !== ''
                    ? $message
                    : '-',
        ];


        /* Notifications */

        send_telegram_application(
            $applicationData
        );

        send_discord_application(
            $applicationData
        );


        $showLoading = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
<link rel="icon" type="image/png" href="files/images/favicon.png">
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Creator Application · Dear SEA Swimwear
</title>

<meta
    name="description"
    content="Apply to collaborate with Dear SEA Swimwear."
>

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;1,9..144,400;1,9..144,500&family=Manrope:wght@300;400;500;600;700&display=swap"
    rel="stylesheet"
>

<?php if ($showLoading): ?>

<meta
    http-equiv="refresh"
    content="4;url=<?php echo htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8'); ?>"
>

<?php endif; ?>


<style>

/* ==========================================================================
   DEAR SEA APPLICATION
   ========================================================================== */

:root{

    --ivory:#F7F2E8;
    --sand:#E9DECC;
    --sand-deep:#DCCDB2;

    --espresso:#352A22;
    --espresso-70:rgba(53,42,34,.70);
    --espresso-45:rgba(53,42,34,.45);

    --ocean:#54707A;
    --ocean-deep:#3E5860;

    --burgundy:#7C3E3F;
    --gold:#E8A87C;

    --white:#FFFDF9;

    --line:rgba(53,42,34,.14);
    --line-soft:rgba(53,42,34,.08);

    --error:#9A4545;

    --shadow:
        0 22px 55px rgba(53,42,34,.10);

    --ease:
        cubic-bezier(.16,.8,.24,1);
}

*{
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{

    margin:0;

    background:var(--ivory);

    color:var(--espresso);

    font-family:'Manrope',sans-serif;

    font-weight:400;

    line-height:1.6;

    -webkit-font-smoothing:antialiased;

    overflow-x:hidden;
}

h1,
h2,
h3,
.serif{

    font-family:'Fraunces',serif;

    font-weight:400;

    letter-spacing:.01em;
}

a{
    color:inherit;
    text-decoration:none;
}

button,
input,
select,
textarea{
    font:inherit;
}

img{
    max-width:100%;
    display:block;
}

::selection{
    background:var(--ocean);
    color:var(--white);
}

:focus-visible{
    outline:2px solid var(--ocean);
    outline-offset:3px;
}


/* ==========================================================================
   LAYOUT
   ========================================================================== */

.apply-page{

    min-height:100vh;

    display:grid;

    grid-template-columns:
        minmax(360px, .92fr)
        minmax(560px, 1.08fr);
}


/* ==========================================================================
   LEFT VISUAL
   ========================================================================== */

.apply-visual{

    position:sticky;

    top:0;

    height:100vh;

    min-height:700px;

    overflow:hidden;

    background:
        linear-gradient(
            145deg,
            var(--sand),
            var(--ocean-deep)
        );

    color:var(--white);

    padding:
        38px
        clamp(28px,4vw,64px)
        44px;

    display:flex;

    flex-direction:column;
}

.apply-visual::before{

    content:"";

    position:absolute;

    inset:0;

    background:
        linear-gradient(
            180deg,
            rgba(53,42,34,.2),
            rgba(53,42,34,.78)
        );

    z-index:1;
}

.apply-visual::after{

    content:"";

    position:absolute;

    inset:0;

    background:
        radial-gradient(
            80% 70% at 50% 30%,
            transparent 25%,
            rgba(53,42,34,.25) 100%
        );

    z-index:1;

    pointer-events:none;
}

.apply-visual > *{
    position:relative;
    z-index:2;
}


/* ==========================================================================
   VISUAL IMAGE
   ========================================================================== */

.visual-image{

    position:absolute;

    inset:0;

    width:100%;

    height:100%;

    object-fit:cover;

    object-position:center;

    opacity:.88;

    transform:scale(1.02);

    transition:
        transform 12s var(--ease),
        opacity .5s ease;
}

.apply-visual:hover .visual-image{
    transform:scale(1.07);
}


/* ==========================================================================
   NAV
   ========================================================================== */

.visual-nav{

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:20px;

    margin-bottom:auto;
}

.visual-logo{

    display:inline-flex;

    align-items:center;

    gap:11px;

    padding:9px 15px 9px 9px;

    background:rgba(255,253,249,.1);

    border:1px solid rgba(255,253,249,.24);

    backdrop-filter:blur(6px);
}

.visual-logo img{

    height:30px;

    width:auto;

    display:block;

    filter:brightness(0) invert(1);
}

.visual-logo-text{

    display:flex;

    flex-direction:column;

    line-height:1.15;
}

.visual-logo-text strong{

    font-family:'Fraunces',serif;

    font-size:.86rem;

    letter-spacing:.03em;

    color:var(--white);
}

.visual-logo-text span{

    font-size:.54rem;

    letter-spacing:.15em;

    text-transform:uppercase;

    color:rgba(255,253,249,.65);
}

.visual-mute{

    position:absolute;

    top:38px;

    right:clamp(28px,4vw,64px);

    z-index:3;

    width:36px;

    height:36px;

    border-radius:50%;

    border:1px solid rgba(255,253,249,.5);

    background:rgba(53,42,34,.35);

    color:var(--white);

    backdrop-filter:blur(6px);

    display:flex;

    align-items:center;

    justify-content:center;

    transition:background .25s;
}

.visual-mute:hover{
    background:rgba(53,42,34,.6);
}

.visual-mute svg{
    width:16px;
    height:16px;
}

.visual-mute.is-muted svg path:last-child{
    opacity:.35;
}

.back-link{

    display:inline-flex;

    align-items:center;

    gap:9px;

    font-size:.68rem;

    letter-spacing:.14em;

    text-transform:uppercase;

    font-weight:700;

    color:rgba(255,253,249,.88);

    padding-bottom:5px;

    border-bottom:1px solid
        rgba(255,253,249,.4);

    transition:
        border-color .25s,
        color .25s;
}

.back-link:hover{

    color:var(--white);

    border-color:var(--white);
}


/* ==========================================================================
   VISUAL CONTENT — eyebrow + tags grouped up top, title/copy below,
   note pinned as a boxed card at the very bottom.
   ========================================================================== */

.visual-content{

    max-width:560px;
}

.visual-top-row{

    display:flex;

    align-items:center;

    flex-wrap:wrap;

    gap:14px 10px;

    margin-bottom:24px;
}

.visual-eyebrow{

    display:inline-flex;

    align-items:center;

    gap:10px;

    font-size:.66rem;

    letter-spacing:.2em;

    text-transform:uppercase;

    font-weight:700;

    color:var(--gold);
}

.visual-eyebrow::before{

    content:"";

    width:20px;

    height:1px;

    background:currentColor;

    opacity:.75;
}

.visual-tags{

    display:flex;

    flex-wrap:wrap;

    gap:8px;
}

.visual-tags span{

    border:
        1px solid
        rgba(255,253,249,.32);

    background:rgba(255,253,249,.06);

    padding:6px 12px;

    font-size:.6rem;

    letter-spacing:.09em;

    text-transform:uppercase;

    color:
        rgba(255,253,249,.9);
}

.visual-title{

    font-size:
        clamp(
            2.7rem,
            5.2vw,
            4.6rem
        );

    line-height:1.02;

    color:var(--white);

    margin:0 0 22px;
}

.visual-title em{

    font-style:italic;

    color:var(--gold);
}

.visual-copy{

    max-width:470px;

    font-size:.95rem;

    line-height:1.85;

    color:
        rgba(255,253,249,.86);

    margin:0;
}


/* ---------- note: boxed card, pinned to bottom of panel ---------- */

.visual-note{

    margin-top:auto;

    padding-top:34px;
}

.visual-note-card{

    background:rgba(255,253,249,.08);

    border:1px solid rgba(255,253,249,.2);

    border-radius:4px;

    backdrop-filter:blur(6px);

    padding:20px 22px;

    max-width:500px;

    display:flex;

    gap:16px;

    align-items:flex-start;
}

.visual-note-icon{

    flex:0 0 34px;

    width:34px;

    height:34px;

    border-radius:50%;

    background:rgba(232,168,124,.16);

    border:1px solid rgba(232,168,124,.5);

    display:grid;

    place-items:center;

    color:var(--gold);
}

.visual-note-icon svg{
    width:15px;
    height:15px;
}

.visual-note-card strong{

    display:block;

    font-size:.67rem;

    letter-spacing:.12em;

    text-transform:uppercase;

    margin-bottom:6px;

    color:var(--gold);
}

.visual-note-card p{

    margin:0;

    color:
        rgba(255,253,249,.82);

    font-size:.8rem;

    line-height:1.7;
}


/* ---------- social links row ---------- */

.visual-social{

    display:flex;

    align-items:center;

    flex-wrap:wrap;

    gap:8px 10px;

    margin-top:18px;
}

.visual-social-label{

    font-size:.6rem;

    letter-spacing:.14em;

    text-transform:uppercase;

    font-weight:700;

    color:rgba(255,253,249,.5);

    width:100%;

    margin-bottom:2px;
}

.visual-social a{

    display:inline-flex;

    align-items:center;

    gap:6px;

    font-size:.68rem;

    letter-spacing:.03em;

    color:rgba(255,253,249,.85);

    border:1px solid rgba(255,253,249,.24);

    padding:6px 12px;

    transition:background .25s, border-color .25s, color .25s;
}

.visual-social a:hover{

    background:rgba(255,253,249,.12);

    border-color:rgba(255,253,249,.5);

    color:var(--white);
}

.visual-social a svg{
    width:12px;
    height:12px;
}


/* ==========================================================================
   RIGHT CONTENT
   ========================================================================== */

.apply-content{

    min-height:100vh;

    background:var(--ivory);

    padding:
        62px
        clamp(28px,5vw,78px)
        80px;

    display:flex;

    justify-content:center;
}

.form-shell{

    width:100%;

    max-width:660px;

    margin:0 auto;
}

.form-header{

    margin-bottom:32px;

    text-align:center;
}

.form-eyebrow{

    display:block;

    color:var(--espresso-45);

    font-size:.67rem;

    letter-spacing:.2em;

    text-transform:uppercase;

    font-weight:700;

    margin-bottom:13px;
}

.form-header h1{

    font-size:
        clamp(
            2.4rem,
            4vw,
            4rem
        );

    line-height:1;

    margin:0 0 17px;
}

.form-header p{

    max-width:520px;

    color:var(--espresso-70);

    font-size:.9rem;

    line-height:1.8;

    margin:0 auto;
}


/* ==========================================================================
   PROGRESS STEPPER — numbered nodes on a connecting rail, filled
   line shows how far along the applicant is. First node marked
   done once past it; current node highlighted in burgundy.
   ========================================================================== */

.stepper{

    display:flex;

    align-items:flex-start;

    margin:0 0 34px;
}

.step{

    position:relative;

    display:flex;

    flex-direction:column;

    align-items:center;

    gap:10px;

    flex:1;
}

.step:last-child{
    flex:0 0 auto;
}

.step-rail{

    position:absolute;

    top:17px;

    left:50%;

    width:100%;

    height:2px;

    background:var(--line);

    z-index:0;
}

.step:last-child .step-rail{
    display:none;
}

.step.done .step-rail,
.step.active .step-rail{

    background:
        linear-gradient(
            90deg,
            var(--burgundy) 0%,
            var(--burgundy) 50%,
            var(--line) 50%
        );
}

.step.done .step-rail{
    background:var(--burgundy);
}

.step-dot{

    position:relative;

    z-index:1;

    width:34px;

    height:34px;

    flex:0 0 34px;

    border-radius:50%;

    display:grid;

    place-items:center;

    border:1.5px solid var(--line);

    color:var(--espresso-45);

    font-size:.72rem;

    font-weight:700;

    font-family:'Fraunces',serif;

    background:var(--white);

    transition:
        background .35s var(--ease),
        border-color .35s var(--ease),
        color .35s var(--ease),
        transform .35s var(--ease);
}

.step-dot svg{
    width:15px;
    height:15px;
}

.step.done .step-dot{

    background:var(--espresso);

    border-color:var(--espresso);

    color:var(--white);
}

.step.active .step-dot{

    background:var(--burgundy);

    border-color:var(--burgundy);

    color:var(--white);

    transform:scale(1.08);

    box-shadow:0 0 0 5px rgba(124,62,63,.14);
}

.step-label{

    font-size:.62rem;

    letter-spacing:.1em;

    text-transform:uppercase;

    font-weight:700;

    color:var(--espresso-45);

    text-align:center;

    line-height:1.4;

    max-width:88px;
}

.step.done .step-label{

    color:var(--espresso-70);
}

.step.active .step-label{

    color:var(--burgundy);
}



/* ==========================================================================
   NOTICE
   ========================================================================== */

.notice{

    background:var(--sand);

    border:
        1px solid
        var(--line-soft);

    border-radius:3px;

    padding:17px 19px;

    margin-bottom:30px;

    font-size:.78rem;

    color:var(--espresso-70);

    line-height:1.7;
}

.notice strong{
    color:var(--espresso);
}


/* ==========================================================================
   FORM CARD — redesigned field layout
   ========================================================================== */

.form-card{

    background:var(--white);

    border:
        1px solid
        var(--line);

    border-radius:4px;

    box-shadow:var(--shadow);

    padding:
        clamp(24px,4vw,42px);
}

.form-section{

    margin-bottom:34px;

    padding-bottom:30px;

    border-bottom:
        1px solid
        var(--line-soft);
}

.form-section:last-of-type{

    border-bottom:none;

    padding-bottom:0;

    margin-bottom:0;
}

.section-heading{

    display:flex;

    align-items:center;

    gap:14px;

    margin-bottom:22px;
}

.section-num{

    flex:0 0 26px;

    width:26px;

    height:26px;

    border-radius:50%;

    background:var(--espresso);

    color:var(--white);

    display:grid;

    place-items:center;

    font-size:.65rem;

    font-weight:700;

    font-family:'Fraunces',serif;
}

.section-heading span.section-label{

    font-size:.66rem;

    letter-spacing:.16em;

    text-transform:uppercase;

    font-weight:700;

    color:var(--espresso);
}

.section-heading::after{

    content:"";

    height:1px;

    background:var(--line);

    flex:1;
}

.form-row{

    display:grid;

    grid-template-columns:
        repeat(2,minmax(0,1fr));

    gap:18px;

    margin-bottom:18px;
}

.form-row:last-child{
    margin-bottom:0;
}

.form-group{

    min-width:0;

    display:flex;

    flex-direction:column;

    background:var(--ivory);

    border:1px solid var(--line-soft);

    border-radius:3px;

    padding:12px 15px 13px;

    transition:border-color .2s, background .2s;
}

.form-group:focus-within{

    background:var(--white);

    border-color:var(--ocean);
}

.form-group.is-invalid-group{

    border-color:var(--error);
}

.form-group.full{
    grid-column:1 / -1;
}

.form-group label{

    font-size:.62rem;

    letter-spacing:.09em;

    text-transform:uppercase;

    font-weight:700;

    margin-bottom:5px;

    color:var(--espresso-45);
}

.form-group:focus-within label{

    color:var(--ocean-deep);
}

.apply-form input,
.apply-form select,
.apply-form textarea{

    width:100%;

    border:none;

    background:transparent;

    color:var(--espresso);

    border-radius:0;

    min-height:26px;

    padding:0;

    font-size:.9rem;

    transition:color .2s;

    outline:none;
}

.apply-form textarea{

    min-height:88px;

    resize:vertical;

    line-height:1.65;

    padding-top:2px;
}

.apply-form select{

    cursor:pointer;

    appearance:none;

    background-image:
        linear-gradient(45deg,transparent 50%,var(--espresso) 50%),
        linear-gradient(135deg,var(--espresso) 50%,transparent 50%);

    background-position:
        calc(100% - 4px) 10px,
        calc(100% - 9px) 10px;

    background-size:
        5px 5px,
        5px 5px;

    background-repeat:no-repeat;

    padding-right:18px;
}

.apply-form input::placeholder,
.apply-form textarea::placeholder{

    color:
        rgba(53,42,34,.32);
}


/* ==========================================================================
   VALIDATION
   ========================================================================== */

.field-error{

    display:none;

    margin-top:6px;

    color:var(--error);

    font-size:.66rem;

    line-height:1.4;
}

.field-error:not(:empty){
    display:block;
}

.apply-form .is-invalid{
    color:var(--error);
}

.form-group .field-error:not(:empty){

    margin-top:2px;
}


/* ==========================================================================
   HONEYPOT
   ========================================================================== */

.hp{

    position:absolute!important;

    left:-9999px!important;

    top:auto!important;

    width:1px!important;

    height:1px!important;

    overflow:hidden!important;
}


/* ==========================================================================
   CHECKBOXES
   ========================================================================== */

.check-group{

    display:grid;

    gap:13px;
}

.check-group.invalid{

    padding:13px;

    border:
        1px solid
        var(--error);

    background:
        rgba(154,69,69,.03);
}

.check-item{

    display:flex;

    align-items:flex-start;

    gap:11px;

    cursor:pointer;

    color:var(--espresso-70);

    font-size:.75rem;

    line-height:1.55;
}

.check-item input{

    position:absolute;

    opacity:0;

    pointer-events:none;
}

.check-box{

    width:19px;

    height:19px;

    flex:0 0 19px;

    border:
        1px solid
        rgba(53,42,34,.3);

    background:var(--white);

    display:grid;

    place-items:center;

    margin-top:1px;

    transition:
        background .18s,
        border-color .18s;
}

.check-box::after{

    content:"";

    width:5px;

    height:9px;

    border:
        solid
        var(--white);

    border-width:
        0
        2px
        2px
        0;

    transform:
        rotate(45deg)
        scale(0);

    transition:
        transform .18s;
}

.check-item input:checked + .check-box{

    background:var(--espresso);

    border-color:var(--espresso);
}

.check-item input:checked + .check-box::after{

    transform:
        rotate(45deg)
        scale(1);
}


/* ==========================================================================
   ALERT
   ========================================================================== */

.form-alert{

    background:
        rgba(124,62,63,.07);

    border:
        1px solid
        rgba(124,62,63,.22);

    padding:13px 15px;

    margin-top:20px;

    color:#7C3E3F;

    font-size:.75rem;
}


/* ==========================================================================
   SUBMIT
   ========================================================================== */

.form-submit{

    width:100%;

    margin-top:26px;

    border:
        1px solid
        var(--espresso);

    border-radius:3px;

    background:var(--espresso);

    color:var(--white);

    min-height:55px;

    padding:0 20px;

    font-size:.68rem;

    letter-spacing:.16em;

    text-transform:uppercase;

    font-weight:700;

    cursor:pointer;

    transition:
        background .3s,
        color .3s,
        border-color .3s,
        transform .3s;
}

.form-submit:hover{

    background:var(--ocean-deep);

    border-color:var(--ocean-deep);

    transform:translateY(-1px);
}

.form-submit:active{
    transform:translateY(0);
}

.form-note{

    margin:13px 0 0;

    text-align:center;

    color:var(--espresso-45);

    font-size:.62rem;

    line-height:1.6;
}


/* ==========================================================================
   LOADING / SUCCESS
   ========================================================================== */

.loading{

    min-height:100vh;

    display:grid;

    place-items:center;

    padding:25px;

    background:
        radial-gradient(
            100% 100% at 50% 0%,
            var(--sand) 0%,
            var(--ocean-deep) 100%
        );
}

.loading-card{

    width:min(460px,100%);

    background:var(--white);

    padding:
        44px
        38px;

    text-align:center;

    box-shadow:
        0 30px 80px
        rgba(53,42,34,.25);
}

.loading-mark{

    margin-bottom:22px;

    display:flex;

    justify-content:center;
}

.loading-mark img{

    height:38px;

    width:auto;
}

.loading-card h2{

    font-size:2.1rem;

    margin:0 0 12px;
}

.loading-card p{

    color:var(--espresso-70);

    font-size:.82rem;

    line-height:1.7;

    margin:0 0 20px;
}

.loading-reference{

    border:
        1px dashed
        var(--ocean);

    background:
        var(--ivory);

    padding:16px;

    margin-top:20px;
}

.loading-reference small{

    display:block;

    color:var(--espresso-45);

    font-size:.6rem;

    letter-spacing:.12em;

    text-transform:uppercase;

    margin-bottom:7px;
}

.loading-reference strong{

    display:block;

    font-family:'Fraunces',serif;

    font-size:1.55rem;

    letter-spacing:.08em;

    color:var(--burgundy);
}

.spinner{

    width:30px;

    height:30px;

    border:
        2px solid
        rgba(53,42,34,.12);

    border-top-color:
        var(--ocean);

    border-radius:50%;

    animation:
        spin .8s linear infinite;

    margin:
        0
        auto
        20px;
}

@keyframes spin{

    to{
        transform:rotate(360deg);
    }
}


/* ==========================================================================
   RESPONSIVE
   ========================================================================== */

@media(max-width:980px){

    .apply-page{

        grid-template-columns:1fr;
    }

    .apply-visual{

        position:relative;

        height:auto;

        min-height:560px;

        padding:
            28px
            clamp(22px,6vw,48px)
            34px;
    }

    .apply-content{

        min-height:auto;

        padding:
            48px
            clamp(22px,6vw,55px)
            65px;
    }

    .form-shell{

        max-width:600px;
    }

    .form-row{

        grid-template-columns:1fr;

        gap:14px;

        margin-bottom:14px;
    }
}

@media(max-width:650px){

    .apply-visual{

        min-height:540px;
    }

    .visual-title{

        font-size:
            clamp(
                2.4rem,
                12vw,
                3.4rem
            );
    }

    .visual-copy{

        font-size:.85rem;
    }

    .apply-content{

        padding:
            38px
            17px
            50px;
    }

    .form-header h1{

        font-size:2.55rem;
    }

    .step-label{

        font-size:.54rem;

        max-width:60px;
    }

    .step-dot{

        width:28px;

        height:28px;

        flex:0 0 28px;

        font-size:.64rem;
    }

    .step-rail{

        top:14px;
    }

    .form-card{

        padding:
            20px
            16px;
    }

    .visual-nav{

        align-items:flex-start;
    }

    .back-link{

        font-size:0;

        width:35px;

        height:35px;

        display:grid;

        place-items:center;

        border:
            1px solid
            rgba(255,253,249,.4);

        padding:0;
    }

    .back-link svg{

        width:14px;

        height:14px;
    }

    .visual-logo img{

        height:26px;
    }

    .visual-logo-text{
        display:none;
    }

    .visual-mute{

        top:22px;

        right:clamp(22px,6vw,48px);

        width:32px;

        height:32px;
    }

    .visual-note-card{

        flex-direction:column;
        gap:10px;
    }
}

@media(max-width:440px){

    .apply-visual{

        min-height:420px;
    }

    .visual-tags span{

        font-size:.56rem;

        padding:5px 10px;
    }

    .form-header p{

        font-size:.84rem;
    }
}

@media(prefers-reduced-motion:reduce){

    *,
    *::before,
    *::after{

        animation:none!important;

        transition:none!important;

        scroll-behavior:auto!important;
    }
}

</style>

</head>


<body>


<?php if ($showLoading): ?>


<!-- ========================================================================
     SUCCESS / REDIRECT SCREEN
     ======================================================================== -->

<div class="loading">

    <div class="loading-card">

        <div class="loading-mark">
            <img src="data:image/png;base64,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" alt="Dear SEA">
        </div>

        <div class="spinner"></div>

        <h2>
            Application is still pending
        </h2>

        <p>
            Thank you for your interest in collaborating with
            Dear SEA Swimwear. Your application has been received
            and we're taking you to the next step.
        </p>

        <div class="loading-reference">

            <small>
                Your reference number
            </small>

            <strong>
                <?php
                echo htmlspecialchars(
                    $referenceNumber,
                    ENT_QUOTES,
                    'UTF-8'
                );
                ?>
            </strong>

        </div>

        <p style="margin-top:18px;margin-bottom:0;">
            Please keep this reference number for your records.
        </p>

    </div>

</div>


<script>

setTimeout(function(){

    window.location.href =
        <?php echo json_encode($redirectUrl); ?>;

}, 4000);

</script>


<?php else: ?>


<!-- ========================================================================
     APPLICATION PAGE
     ======================================================================== -->

<main class="apply-page">


    <!-- ====================================================================
         LEFT BRAND PANEL
         ==================================================================== -->

    <aside class="apply-visual">


        <!-- Main campaign video (poster shows if the video file isn't found yet) -->
        <video
            class="visual-image"
            id="applyVideo"
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            poster="files/images/1.webp"
        >
            <source src="files/video/play5.mp4" type="video/mp4">
        </video>

        <button class="visual-mute" id="applyMute" aria-label="Mute video">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M4 9v6h4l5 5V4L8 9H4z"/><path d="M17.5 8.5a5 5 0 0 1 0 7" stroke-linecap="round"/></svg>
        </button>


        <!-- Navigation -->

        <div class="visual-nav">

            <a
                href="index.php"
                class="visual-logo"
                aria-label="Dear SEA Swimwear"
            >
                <img src="data:image/png;base64,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" alt="Dear SEA">
                <span class="visual-logo-text">
                    <strong>Dear SEA</strong>
                    <span>Swimwear</span>
                </span>
            </a>

            <a
                href="index.php"
                class="back-link"
                aria-label="Back to Dear SEA"
            >

                <svg
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    aria-hidden="true"
                >
                    <path
                        d="M15 5l-7 7 7 7"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                <span>
                    Back to site
                </span>

            </a>

        </div>


        <!-- Brand copy -->

        <div class="visual-content">

            <div class="visual-top-row">

                <span class="visual-eyebrow">
                    Open Call · Summer Collective
                </span>

                <div class="visual-tags">

                    <span>
                        Creator collaborations
                    </span>

                    <span>
                        Product features
                    </span>

                    <span>
                        Summer campaigns
                    </span>

                </div>

            </div>

            <h1 class="visual-title">

                Create
                <br>

                <em>with Dear SEA.</em>

            </h1>

            <p class="visual-copy">

                Dear SEA Swimwear is building a community
                of creators, models, photographers, stylists,
                and creatives who share our love for swim,
                summer, and effortless coastal style.

            </p>

        </div>


        <!-- Closing note — boxed card, pinned to the bottom of the panel -->

        <div class="visual-note">

            <div class="visual-note-card">

                <span class="visual-note-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2l2.4 6.6L21 11l-6.6 2.4L12 20l-2.4-6.6L3 11l6.6-2.4L12 2z" stroke-linejoin="round"/></svg>
                </span>

                <span>
                    <strong>
                        What we're looking for
                    </strong>

                    <p>

                        We care about your creative point of view,
                        styling, content quality, and audience connection.
                        You don't need to be the biggest creator in the room —
                        we want people who genuinely fit the Dear SEA world.

                    </p>
                </span>

            </div>

            <div class="visual-social">
                <span class="visual-social-label">Follow &amp; Shop Dear SEA</span>

                <a href="https://www.instagram.com/dearseastories/" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="3.5"/><circle cx="17.2" cy="6.8" r="1"/></svg>
                    Instagram
                </a>

                <a href="https://tiktok.com/dearsea" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5" stroke-linecap="round"/><path d="M14 3c0 2.5 2 4.5 4.5 4.5" stroke-linecap="round"/></svg>
                    TikTok
                </a>

                <a href="http://dearseasummer.com/" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.8 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.8-3.8-9s1.3-6.5 3.8-9z"/></svg>
                    Website
                </a>

                <a href="https://shopee.ph/dearsea" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 0 1 6 0v2" stroke-linecap="round"/></svg>
                    Shopee
                </a>

                <a href="https://www.lazada.com.ph/shop/dear-sea" target="_blank" rel="noopener">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8h12l-1 12H7L6 8z" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 0 1 6 0v2" stroke-linecap="round"/></svg>
                    Lazada
                </a>
            </div>

        </div>

    </aside>


    <!-- ====================================================================
         RIGHT APPLICATION AREA
         ==================================================================== -->

    <section class="apply-content">

        <div class="form-shell">


            <!-- Header -->

            <div class="form-header">

                <span class="form-eyebrow">
                    Looking for collaboration
                </span>

                <h1>
                    Become a Dear SEA Creator
                </h1>

                <p>

                    Join a growing roster of creators getting free
                    product, paid campaigns, and real exposure — tell
                    us a little about yourself, your content, and your
                    creative style below. Takes about 3 minutes.

                </p>

            </div>


            <!-- Progress stepper -->

            <div class="stepper">

                <div class="step active">

                    <span class="step-rail"></span>

                    <span class="step-dot">
                        1
                    </span>

                    <span class="step-label">
                        Apply
                    </span>

                </div>


                <div class="step">

                    <span class="step-rail"></span>

                    <span class="step-dot">
                        2
                    </span>

                    <span class="step-label">
                        Verify
                    </span>

                </div>


                <div class="step">

                    <span class="step-rail"></span>

                    <span class="step-dot">
                        3
                    </span>

                    <span class="step-label">
                        Connect
                    </span>

                </div>


                <div class="step">

                    <span class="step-dot">
                        4
                    </span>

                    <span class="step-label">
                        Collaborate
                    </span>

                </div>

            </div>


            <!-- Notice -->

            <div class="notice">

                <strong>
                    Only takes 3 minutes to apply.
                </strong>

                Fill out the form below — the more complete and
                accurate your details, the faster our team can
                review and get back to you.

            </div>


            <!-- Form -->

            <div class="form-card">

                <form
                    method="POST"
                    class="apply-form"
                    id="applyForm"
                    autocomplete="off"
                    novalidate
                >


                    <!-- CSRF -->

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php
                        echo htmlspecialchars(
                            $_SESSION['csrf_token'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        ?>"
                    >


                    <!-- Honeypot -->

                    <div
                        class="hp"
                        aria-hidden="true"
                    >

                        <label>
                            Leave this field empty

                            <input
                                type="text"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                            >

                        </label>

                    </div>


                    <!-- ====================================================
                         ABOUT YOU
                         ==================================================== -->

                    <div class="form-section">

                        <div class="section-heading">
                            <span class="section-num">1</span>
                            <span class="section-label">
                                About you
                            </span>
                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label for="full_name">
                                    Full name *
                                </label>

                                <input
                                    type="text"
                                    id="full_name"
                                    name="full_name"
                                    value="<?php echo val('full_name'); ?>"
                                    placeholder="Your full name"
                                    class="<?php echo trim(err_class('full_name')); ?>"
                                    required
                                >

                                <?php echo err_html('full_name'); ?>

                            </div>


                            <div class="form-group">

                                <label for="email">
                                    Email address *
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="<?php echo val('email'); ?>"
                                    placeholder="you@email.com"
                                    class="<?php echo trim(err_class('email')); ?>"
                                    required
                                >

                                <?php echo err_html('email'); ?>

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label for="phone">
                                    Phone number *
                                </label>

                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="<?php
                                    echo val('phone') !== ''
                                        ? val('phone')
                                        : '639';
                                    ?>"
                                    placeholder="639XXXXXXXXX"
                                    inputmode="numeric"
                                    maxlength="12"
                                    minlength="12"
                                    class="<?php echo trim(err_class('phone')); ?>"
                                    required
                                >

                                <?php echo err_html('phone'); ?>

                            </div>


                            <div class="form-group">

                                <label for="location">
                                    City / Province *
                                </label>

                                <input
                                    type="text"
                                    id="location"
                                    name="location"
                                    value="<?php echo val('location'); ?>"
                                    placeholder="Your location"
                                    class="<?php echo trim(err_class('location')); ?>"
                                    required
                                >

                                <?php echo err_html('location'); ?>

                            </div>

                        </div>

                    </div>


                    <!-- ====================================================
                         CREATOR PROFILE
                         ==================================================== -->

                    <div class="form-section">

                        <div class="section-heading">
                            <span class="section-num">2</span>
                            <span class="section-label">
                                Your creator profile
                            </span>
                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label for="role">
                                    I am a... *
                                </label>

                                <select
                                    id="role"
                                    name="role"
                                    class="<?php echo trim(err_class('role')); ?>"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        <?php echo sel('role',''); ?>
                                    >
                                        Select one
                                    </option>

                                    <option
                                        value="Content creator"
                                        <?php echo sel('role','Content creator'); ?>
                                    >
                                        Content creator
                                    </option>

                                    <option
                                        value="Model"
                                        <?php echo sel('role','Model'); ?>
                                    >
                                        Model
                                    </option>

                                    <option
                                        value="Photographer"
                                        <?php echo sel('role','Photographer'); ?>
                                    >
                                        Photographer
                                    </option>

                                    <option
                                        value="Stylist"
                                        <?php echo sel('role','Stylist'); ?>
                                    >
                                        Stylist
                                    </option>

                                    <option
                                        value="Videographer"
                                        <?php echo sel('role','Videographer'); ?>
                                    >
                                        Videographer
                                    </option>

                                    <option
                                        value="Creative / Other"
                                        <?php echo sel('role','Creative / Other'); ?>
                                    >
                                        Creative / Other
                                    </option>

                                </select>

                                <?php echo err_html('role'); ?>

                            </div>


                            <div class="form-group">

                                <label for="platform">
                                    Main platform *
                                </label>

                                <select
                                    id="platform"
                                    name="platform"
                                    class="<?php echo trim(err_class('platform')); ?>"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        <?php echo sel('platform',''); ?>
                                    >
                                        Select platform
                                    </option>

                                    <option
                                        value="Instagram"
                                        <?php echo sel('platform','Instagram'); ?>
                                    >
                                        Instagram
                                    </option>

                                    <option
                                        value="TikTok"
                                        <?php echo sel('platform','TikTok'); ?>
                                    >
                                        TikTok
                                    </option>

                                    <option
                                        value="Facebook"
                                        <?php echo sel('platform','Facebook'); ?>
                                    >
                                        Facebook
                                    </option>

                                    <option
                                        value="YouTube"
                                        <?php echo sel('platform','YouTube'); ?>
                                    >
                                        YouTube
                                    </option>

                                    <option
                                        value="Multiple platforms"
                                        <?php echo sel('platform','Multiple platforms'); ?>
                                    >
                                        Multiple platforms
                                    </option>

                                </select>

                                <?php echo err_html('platform'); ?>

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label for="followers">
                                    Total followers *
                                </label>

                                <input
                                    type="text"
                                    id="followers"
                                    name="followers"
                                    value="<?php echo val('followers'); ?>"
                                    placeholder="e.g. 12500"
                                    inputmode="numeric"
                                    maxlength="9"
                                    class="<?php echo trim(err_class('followers')); ?>"
                                    required
                                >

                                <?php echo err_html('followers'); ?>

                            </div>


                            <div class="form-group">

                                <label for="content_type">
                                    Main content focus *
                                </label>

                                <select
                                    id="content_type"
                                    name="content_type"
                                    class="<?php echo trim(err_class('content_type')); ?>"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        <?php echo sel('content_type',''); ?>
                                    >
                                        Select focus
                                    </option>

                                    <option
                                        value="Swimwear & fashion"
                                        <?php echo sel('content_type','Swimwear & fashion'); ?>
                                    >
                                        Swimwear & fashion
                                    </option>

                                    <option
                                        value="Lifestyle & travel"
                                        <?php echo sel('content_type','Lifestyle & travel'); ?>
                                    >
                                        Lifestyle & travel
                                    </option>

                                    <option
                                        value="Beauty & lifestyle"
                                        <?php echo sel('content_type','Beauty & lifestyle'); ?>
                                    >
                                        Beauty & lifestyle
                                    </option>

                                    <option
                                        value="Summer / beach content"
                                        <?php echo sel('content_type','Summer / beach content'); ?>
                                    >
                                        Summer / beach content
                                    </option>

                                    <option
                                        value="Modeling / editorial"
                                        <?php echo sel('content_type','Modeling / editorial'); ?>
                                    >
                                        Modeling / editorial
                                    </option>

                                    <option
                                        value="Mixed content"
                                        <?php echo sel('content_type','Mixed content'); ?>
                                    >
                                        Mixed content
                                    </option>

                                </select>

                                <?php echo err_html('content_type'); ?>

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group full">

                                <label for="links">
                                    Social media links *
                                </label>

                                <textarea
                                    id="links"
                                    name="links"
                                    placeholder="Paste your Instagram, TikTok, YouTube, or other active profile links"
                                    class="<?php echo trim(err_class('links')); ?>"
                                    required
                                ><?php echo val('links'); ?></textarea>

                                <?php echo err_html('links'); ?>

                            </div>

                        </div>

                    </div>


                    <!-- ====================================================
                         COLLABORATION
                         ==================================================== -->

                    <div class="form-section">

                        <div class="section-heading">
                            <span class="section-num">3</span>
                            <span class="section-label">
                                Collaboration
                            </span>
                        </div>


                        <div class="form-row">

                            <div class="form-group">

                                <label for="preference">
                                    Collaboration preference *
                                </label>

                                <select
                                    id="preference"
                                    name="preference"
                                    class="<?php echo trim(err_class('preference')); ?>"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        <?php echo sel('preference',''); ?>
                                    >
                                        Select preference
                                    </option>

                                    <option
                                        value="Product exchange"
                                        <?php echo sel('preference','Product exchange'); ?>
                                    >
                                        Product exchange
                                    </option>

                                    <option
                                        value="Paid collaboration"
                                        <?php echo sel('preference','Paid collaboration'); ?>
                                    >
                                        Paid collaboration
                                    </option>

                                    <option
                                        value="Open to discussion"
                                        <?php echo sel('preference','Open to discussion'); ?>
                                    >
                                        Open to discussion
                                    </option>

                                    <option
                                        value="Long-term partnership"
                                        <?php echo sel('preference','Long-term partnership'); ?>
                                    >
                                        Long-term partnership
                                    </option>

                                </select>

                                <?php echo err_html('preference'); ?>

                            </div>


                            <div class="form-group">

                                <label for="source">
                                    How did you hear about us? *
                                </label>

                                <select
                                    id="source"
                                    name="source"
                                    class="<?php echo trim(err_class('source')); ?>"
                                    required
                                >

                                    <option
                                        value=""
                                        disabled
                                        <?php echo sel('source',''); ?>
                                    >
                                        Select one
                                    </option>

                                    <option
                                        value="Instagram"
                                        <?php echo sel('source','Instagram'); ?>
                                    >
                                        Instagram
                                    </option>

                                    <option
                                        value="TikTok"
                                        <?php echo sel('source','TikTok'); ?>
                                    >
                                        TikTok
                                    </option>

                                    <option
                                        value="Facebook"
                                        <?php echo sel('source','Facebook'); ?>
                                    >
                                        Facebook
                                    </option>

                                    <option
                                        value="Friend / referral"
                                        <?php echo sel('source','Friend / referral'); ?>
                                    >
                                        Friend / referral
                                    </option>

                                    <option
                                        value="Google / search"
                                        <?php echo sel('source','Google / search'); ?>
                                    >
                                        Google / search
                                    </option>

                                    <option
                                        value="Other"
                                        <?php echo sel('source','Other'); ?>
                                    >
                                        Other
                                    </option>

                                </select>

                                <?php echo err_html('source'); ?>

                            </div>

                        </div>


                        <div class="form-row">

                            <div class="form-group full">

                                <label for="message">
                                    Tell us about your content
                                    <span style="font-weight:400;color:var(--espresso-45);">
                                        (optional)
                                    </span>
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    placeholder="Tell us about your creative style, audience, previous work, or why you feel you'd be a good fit for Dear SEA."
                                ><?php echo val('message'); ?></textarea>

                            </div>

                        </div>

                    </div>


                    <!-- ====================================================
                         CONFIRM
                         ==================================================== -->

                    <div class="form-section">

                        <div class="section-heading">
                            <span class="section-num">4</span>
                            <span class="section-label">
                                Before you submit
                            </span>
                        </div>


                        <div
                            class="check-group<?php
                            echo !empty($fieldErrors['checks'])
                                ? ' invalid'
                                : '';
                            ?>"
                            id="checkGroup"
                        >

                            <label class="check-item">

                                <input
                                    type="checkbox"
                                    name="chk1"
                                    required
                                    <?php
                                    echo isset($_POST['chk1'])
                                        ? ' checked'
                                        : '';
                                    ?>
                                >

                                <span
                                    class="check-box"
                                    aria-hidden="true"
                                ></span>

                                <span>
                                    My social media links are
                                    correct and active.
                                </span>

                            </label>


                            <label class="check-item">

                                <input
                                    type="checkbox"
                                    name="chk2"
                                    required
                                    <?php
                                    echo isset($_POST['chk2'])
                                        ? ' checked'
                                        : '';
                                    ?>
                                >

                                <span
                                    class="check-box"
                                    aria-hidden="true"
                                ></span>

                                <span>
                                    I understand that applications
                                    are subject to review and
                                    campaign availability.
                                </span>

                            </label>


                            <label class="check-item">

                                <input
                                    type="checkbox"
                                    name="chk3"
                                    required
                                    <?php
                                    echo isset($_POST['chk3'])
                                        ? ' checked'
                                        : '';
                                    ?>
                                >

                                <span
                                    class="check-box"
                                    aria-hidden="true"
                                ></span>

                                <span>
                                    I agree to be contacted regarding
                                    Dear SEA creator opportunities
                                    and campaign updates.
                                </span>

                            </label>


                            <label class="check-item">

                                <input
                                    type="checkbox"
                                    name="chk4"
                                    required
                                    <?php
                                    echo isset($_POST['chk4'])
                                        ? ' checked'
                                        : '';
                                    ?>
                                >

                                <span
                                    class="check-box"
                                    aria-hidden="true"
                                ></span>

                                <span>
                                    I understand that my submitted
                                    information and public content
                                    may be reviewed by the Dear SEA team.
                                </span>

                            </label>

                        </div>


                        <?php echo err_html('checks'); ?>


                        <?php if ($formError !== ''): ?>

                            <div class="form-alert">

                                <?php
                                echo htmlspecialchars(
                                    $formError,
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>

                            </div>

                        <?php endif; ?>


                        <button
                            type="submit"
                            class="form-submit"
                        >
                            Submit creator application
                        </button>

                        <p class="form-note">

                            Please double-check every detail before
                            submitting — accurate info means a faster
                            review and no back-and-forth on our end.

                        </p>

                    </div>

                </form>

            </div>

        </div>

    </section>

</main>


<script>

/* ==========================================================================
   BACKGROUND VIDEO + MUTE TOGGLE
   ========================================================================== */

(function(){

    const video = document.getElementById('applyVideo');
    const muteBtn = document.getElementById('applyMute');

    if(!video || !muteBtn) return;

    // Belt-and-suspenders autoplay attributes — some browsers only
    // honor the raw HTML attribute form, not just the JS property.
    video.muted = true;
    video.setAttribute('muted', '');
    video.setAttribute('autoplay', '');
    video.setAttribute('playsinline', '');
    video.setAttribute('webkit-playsinline', '');

    muteBtn.classList.toggle('is-muted', video.muted);

    const tryPlay = function(){ video.play().catch(function(){}); };
    video.addEventListener('loadeddata', tryPlay, {once:true});
    video.addEventListener('canplay', tryPlay, {once:true});
    tryPlay();

    muteBtn.addEventListener('click', function(){
        video.muted = !video.muted;
        muteBtn.classList.toggle('is-muted', video.muted);
    });

    // Safety net: retry play on the first real user interaction, in
    // case a browser's autoplay policy blocked the automatic attempt.
    document.addEventListener('click', function(){
        if(video.paused) video.play().catch(function(){});
    }, {once:true});

})();


/* ==========================================================================
   PHONE NUMBER
   ========================================================================== */

(function(){

    const phone =
        document.getElementById('phone');

    if(!phone) return;


    phone.addEventListener(
        'input',
        function(){

            let value =
                phone.value.replace(/\D/g,'');


            if(!value.startsWith('639')){

                if(value.startsWith('09')){

                    value =
                        '639' +
                        value.substring(2);

                }else if(value.startsWith('9')){

                    value =
                        '63' +
                        value;

                }else{

                    value =
                        '639' +
                        value.replace(/^0+/,'');
                }
            }


            phone.value =
                value.substring(0,12);
        }
    );


    phone.addEventListener(
        'keydown',
        function(e){

            const pos =
                phone.selectionStart;

            if(
                (
                    e.key === 'Backspace' ||
                    e.key === 'Delete'
                ) &&
                pos <= 3
            ){

                e.preventDefault();
            }
        }
    );


    phone.addEventListener(
        'paste',
        function(e){

            e.preventDefault();

            let pasted =
                (
                    e.clipboardData ||
                    window.clipboardData
                )
                .getData('text')
                .replace(/\D/g,'');


            if(pasted.startsWith('09')){

                pasted =
                    '639' +
                    pasted.substring(2);

            }else if(pasted.startsWith('9')){

                pasted =
                    '63' +
                    pasted;

            }else if(
                !pasted.startsWith('639')
            ){

                pasted =
                    '639';
            }


            phone.value =
                pasted.substring(0,12);
        }
    );

})();


/* ==========================================================================
   FOLLOWERS
   ========================================================================== */

(function(){

    const followers =
        document.getElementById('followers');

    if(!followers) return;


    followers.addEventListener(
        'input',
        function(){

            followers.value =
                followers.value
                    .replace(/\D/g,'')
                    .substring(0,9);
        }
    );

})();


/* ==========================================================================
   LIVE VALIDATION
   ========================================================================== */

(function(){

    const form =
        document.getElementById('applyForm');

    if(!form) return;


    const rules = {

        full_name:
            value =>
                value.trim() !== '' ||
                'Please enter your full name.',

        email:
            value =>
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/
                    .test(value.trim()) ||
                'Please enter a valid email address.',

        phone:
            value =>
                /^639\d{9}$/
                    .test(value.trim()) ||
                'Use 639 followed by 9 digits.',

        location:
            value =>
                value.trim() !== '' ||
                'Please enter your city or province.',

        role:
            value =>
                value !== '' ||
                'Please select what best describes you.',

        platform:
            value =>
                value !== '' ||
                'Please select your main platform.',

        followers:
            value =>
                /^\d{1,9}$/
                    .test(value.trim()) ||
                'Numbers only, up to 9 digits.',

        links:
            value =>
                value.trim() !== '' ||
                'Please add at least one social media link.',

        content_type:
            value =>
                value !== '' ||
                'Please select your main content focus.',

        preference:
            value =>
                value !== '' ||
                'Please select your collaboration preference.',

        source:
            value =>
                value !== '' ||
                'Please tell us how you heard about Dear SEA.'
    };


    function getErrorElement(group){

        let error =
            group.querySelector('.field-error');

        if(!error){

            error =
                document.createElement('small');

            error.className =
                'field-error';

            group.appendChild(error);
        }

        return error;
    }


    function checkField(
        name,
        markValid
    ){

        const element =
            form.elements[name];

        if(
            !element ||
            !rules[name]
        ){

            return true;
        }


        const group =
            element.closest('.form-group');

        const result =
            rules[name](element.value);

        const error =
            getErrorElement(group);


        if(result === true){

            element.classList.remove(
                'is-invalid'
            );

            group.classList.remove(
                'is-invalid-group'
            );

            if(
                markValid &&
                element.value.trim() !== ''
            ){

                element.classList.add(
                    'is-valid'
                );
            }

            error.textContent = '';

            return true;
        }


        element.classList.remove(
            'is-valid'
        );

        element.classList.add(
            'is-invalid'
        );

        group.classList.add(
            'is-invalid-group'
        );

        error.textContent =
            result;

        return false;
    }


    const checkGroup =
        document.getElementById(
            'checkGroup'
        );

    const boxes = [
        'chk1',
        'chk2',
        'chk3',
        'chk4'
    ].map(
        name => form.elements[name]
    );


    function checkBoxes(){

        const ok =
            boxes.every(
                box =>
                    box &&
                    box.checked
            );


        const parent =
            checkGroup.parentElement;

        let error =
            parent.querySelector(
                '.field-error'
            );


        if(!error){

            error =
                document.createElement('small');

            error.className =
                'field-error';

            parent.appendChild(error);
        }


        if(ok){

            checkGroup.classList.remove(
                'invalid'
            );

            error.textContent = '';

        }else{

            checkGroup.classList.add(
                'invalid'
            );

            error.textContent =
                'Please confirm all four statements to continue.';
        }


        return ok;
    }


    Object.keys(rules)
        .forEach(function(name){

            const element =
                form.elements[name];

            if(!element) return;


            const isSelect =
                element.tagName === 'SELECT';


            element.addEventListener(
                isSelect
                    ? 'change'
                    : 'blur',
                function(){

                    checkField(
                        name,
                        true
                    );
                }
            );


            element.addEventListener(
                'input',
                function(){

                    if(
                        element.classList.contains(
                            'is-invalid'
                        )
                    ){

                        checkField(
                            name,
                            true
                        );
                    }
                }
            );
        });


    boxes.forEach(function(box){

        if(box){

            box.addEventListener(
                'change',
                checkBoxes
            );
        }

    });


    form.addEventListener(
        'submit',
        function(e){

            let firstBad =
                null;


            for(
                const name of
                Object.keys(rules)
            ){

                const element =
                    form.elements[name];

                if(!element) continue;


                if(
                    rules[name](
                        element.value
                    ) !== true
                ){

                    firstBad =
                        name;

                    break;
                }
            }


            if(firstBad){

                e.preventDefault();


                checkField(
                    firstBad,
                    true
                );


                const element =
                    form.elements[firstBad];


                element.focus({
                    preventScroll:false
                });


                element.scrollIntoView({
                    behavior:'smooth',
                    block:'center'
                });


                return;
            }


            if(!checkBoxes()){

                e.preventDefault();

                checkGroup.scrollIntoView({
                    behavior:'smooth',
                    block:'center'
                });
            }

        }
    );

})();

</script>


<?php endif; ?>


</body>

</html>
