<?
require('./inc.php');
require('twitter.php');
start_html('Colorado Springs Fiction Writer\'s Group');
nav('home');
left_sidebar();
$dbh = db_connect();
?>

<?
$msg = $_SESSION['msg'];
if (!empty($msg))
{
?>
<p align="center" class="alert_box"><?=$msg?></p>
<?
$_SESSION['msg'] = "";
}
?>

<table cellpadding="3" cellspacing="3" border="0" width="100%">
  <tr>
    <td valign="top">
<?
$sql = "SELECT web_group_news.datetime_submitted, web_group_news.news_text, web_users.name FROM web_group_news, web_users WHERE web_users.user_id = web_group_news.user_id ORDER BY datetime_submitted DESC LIMIT 1";
$res = mysql_query($sql, $dbh);
list($timestamp, $group_news, $submitter_name) = mysql_fetch_array($res);
$timestamp = htmlentities($timestamp);
$timestamp = explode(" ", $timestamp);
$timestamp = $timestamp[0];
$group_news = chtml($group_news);
$submitter_name = chtml($submitter_name);
?>

      <div align="center"><img src="/images/group_news.png" width="250" height="100" alt="Group News" /></div>

      <h2 align="center"><?=$timestamp?></h2>

      <p><?=$group_news?></p><br />

      <p align="center"><img src="/images/bar.png" alt="----" width="100%" height="6" /></p>

      <h1 align="center"><a href="news_archive.php">News Archive</a><h1>

      <p align="center"><img src="/images/bar.png" alt="----" width="100%" height="6" /></p>

      <p>
      <h1 align="center">This Month's Submissions</h1>
      <table cellpadding="3" cellspacing="3" border="0" align="center">
<?
$sql = "SELECT group_id, day_of_week, day_of_week_short, meeting_location, photo_filename FROM web_groups ORDER BY group_id";
$res = mysql_query($sql, $dbh);
while (list($group_id, $day, $day_short, $loc, $photo) = mysql_fetch_array($res))
{
  $day = htmlentities($day);
  $day_short = htmlentities($day_short);
  $loc = htmlentities($loc);
  $photo = htmlentities($photo);

  $sql = "SELECT last_submissions_uploaded FROM web_meta";
  $last_upload_res = mysql_query($sql, $dbh);
  list($last_upload) = mysql_fetch_array($last_upload_res);
  list($lu_year, $lu_month) = explode('-', $last_upload);

  $lu_year = mysql_real_escape_string($lu_year, $dbh);
  $lu_month = mysql_real_escape_string($lu_month, $dbh);

  $sql = "SELECT
            u.name, s.title
          FROM
            web_users AS u, web_submissions AS s
          WHERE
            s.year = '$lu_year' AND
            s.month = '$lu_month' AND
            s.group_id = $group_id AND
            s.user_id = u.user_id";
  $subs_res = mysql_query($sql, $dbh);

  $subs = array();
  if (mysql_num_rows($subs_res) !== 0)
  {
    while ($row = mysql_fetch_array($subs_res))
    {
      $subs[] = $row;
    }
  }
?>
        <tr>
          <td colspan="2" align="center"><h3><a href="/meetings/index.php?group=<?=$day?>"><?=$day?> Meeting at <?=$loc?></a></h3></td>
        </tr>
        <tr>
          <td>
            <a href="/meetings/index.php?group=<?=$day?>"><img src="/images/locations/<?=$photo?>" width="198" height="150" alt="<?=$day?> Meeting Location" border="0" /></a>
          </td>
          <td valign="top">
<?
  if (count($subs) == 0)
  {
    print("<p align=\"center\"><b>No submissions this month!</b></p>");
  }
  else
  {
    print("<ul>\n");
    foreach ($subs as $sub)
    {
      $name = htmlentities($sub[0]);
      $title = htmlentities(strtoupper($sub[1]));
      print("<li>$title by $name</li>\n");
    }
    print("</ul>\n");
?>
            <p>&nbsp;</p>
<?
    if (logged_in('ALL'))
    {
?>
            <p align="center">
            <a href="/submissions/download.php?day=<?=$day_short?>&year=<?=$lu_year?>&month=<?=$lu_month?>"><img src="/images/downloadzip.png" width="85" height="12" border="0"/></a>
            </p>
<?
    }
  }
?>
          </td>
        </tr>
        <tr><td colspan="2" align="center"><img src="/images/bar.png" width="80%" height="6" alt="----" /></td></tr>

<?
}
?>
      </table>
      </p>

    <!-- right menu -->
    </td>
    <td valign="top">
      <table cellpadding="3" cellspacing="3" border="0" width="100%">
        <tr>
          <td valign="top" align="center">
            <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="176" height="225" id="CoffeeCupNewsReader" align="middle">
              <param name="movie" value="/coffeecup.swf"/>
              <param name="quality" value="high" />
              <param name="scale" value="noscale" />
              <param name="salign" value="lt" />
              <param name="bgcolor" value="#ffffff" />
              <embed src="/coffeecup.swf" quality="high" bgcolor="#ffffff" width="176" height="225" name="CoffeeCupNewsReader" scale="noscale" salign="lt" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
            </object>
          </td>
        </tr>
        <tr>
          <td valign="top">
            <div style="border: 3px solid #663300; padding: 3px;">
              <h3 align="center" style="margin: 0px 0px 5px 0px; padding: 0px 0px 0px 0px; text-decoration: underline;">CSFWG Twitter Feed</h3>
              <h3 align="center" style="margin: 0px 0px 5px 0px; padding: 0px 0px 0px 0px; text-decoration: underline;"><a href="https://twitter.com/csfwg" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @csfwg</a></h3>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
              <?=$tweetout;?>
            </div>
          </td>
        </tr>
        <tr>

<?
$sql = "
    SELECT name,
           word_count
      FROM NANOWRIMO
INNER JOIN web_users
        ON NANOWRIMO.user_id
         = web_users.user_id
  ORDER BY word_count
      DESC";

$res = mysql_query($sql, $dbh);
?>

<table align="center">
<tr><td colspan=2><h4 align="center">NaNoWriMo Word Counts</h4></td></tr>
<?
$total = 0;
while (list($name, $count) = mysql_fetch_array($res))
{
?>
<tr><td><?=$name?></td><td><?=number_format($count)?></td></tr>
<?
$total = $total + $count;
}
?>
<hr><td><h4>CSFWG Grand Total</h4></td><td><h4><?=number_format($total)?></h4></td></hr>
</table>

<?
if(logged_in('members_and_alumnus'))
{
?>
<hr/>
<table align="center">
<tr><td>Participating in NaNoWriMo?<br/>Keep us up to date on your total word count!</td></tr>
<tr><td>
  <form method="post" action="nanowrimo.php">
    <input type="number" name="count" id="count" size="12">
    <input type="submit" name="submit" value="Update" class="input_button" />
  </form>
</td></tr>
</table>
<?
}
?>

        </tr>
      </table>
    </td>
  </tr>
</table>

<?
mysql_close($dbh);
right_sidebar();
end_html();
?>