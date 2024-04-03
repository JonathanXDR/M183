<?php
require_once 'fw/ElasticSearchLogger.php';
$logger = new ElasticSearchLogger();

if (!isset($_COOKIE['username'])) {
    $logger->log('WARN', 'Unauthorized background search attempt', ['cookie' => $_COOKIE]);
    header("Location: ../login.php");
    exit();
}

$username = $_COOKIE['username'];
$logger->log('INFO', 'Initiating background search', ['username' => $username]);

require_once 'fw/db.php';
?>
<section id="search">
    <h2>Search</h2>
    <form id="form" method="post" action="">
        <input type="hidden" id="searchurl" name="searchurl" value="/search/v2/" />
        <div class="form-group">
            <label for="terms">terms</label>
            <input type="text" class="form-control size-medium" name="terms" id="terms">
        </div>
        <div class="form-group">
            <label for="submit"></label>
            <input id="submit" type="submit" class="btn size-auto" value="Submit" />
        </div>
    </form>
    <div id="messages">
        <div id="msg" class="hidden">The search is running. Results will be visible soon.</div>
        <div id="result" class="hidden"></div>
    </div>
    <script>
        $(document).ready(function () {
            $('#form').validate({
                rules: {
                    terms: {
                        required: true
                    }
                },
                messages: {
                    terms: 'Please enter search terms.',
                },
                submitHandler: function (form) {
                    let provider = $("#searchurl").val();
                    let terms = $("#terms").val();
                    let userID =
                        <?php echo htmlspecialchars($_COOKIE["userID"], ENT_QUOTES, 'UTF-8'); ?>;
                    $("#msg").show();
                    $("#result").html("");
                    $.post(provider, {
                        terms: terms,
                        userID: userID
                    }, function (data) {
                        $("#result").html(data);
                        $("#msg").hide(500);
                        $("#result").show(500);
                    });
                    return false;
                }
            });
        });
    </script>
</section>