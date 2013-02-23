<?
include("header.php");
?>
<?
	echo "&nbsp;&nbsp;".$session->lang->systemText["SITE_CHOOSEFROMMENU"]."<br><br>";
	
	
	
	$sql = "SELECT id, keyvalue, title, href, textvalue FROM cms_help ORDER BY lp";
	
	$res = $session->base->dql($sql);
	
?>
	<table>
		<tr>
			<td width="200">
				<table><tr><td><b><?=$session->lang->systemText["SITE_HELPTOPICS"]?></b><br><br></td></tr>
				
<?
	
	$i = 0;
	foreach($res AS $row) {
	
		if ($row[1] == "" || $user->hasRight($row[1])) {
			$i++;
?>
			<tr>
				<td style="padding-top: 3px;padding-bottom: 3px; border-top: 1px solid #e1e1e1;">
			
				<a href="<?=$_SERVER['PHP_SELF']?>?href=<?=$row[3]?>"><b><?=$i . ". " . $row[2]?></b></a>
				</td>
			</tr>
			
		
<?
		}
	}
?>
				</table>
			</td>
			<td>
				<table>
					<tr>
						<td>
					<?
						$sql = "SELECT id, keyvalue, title, href, textvalue FROM cms_help WHERE href='".$session->getPRPar("href")."'";
	
						$res = $session->base->dql($sql);
						if (count($res) == 1)
							echo "<b style='color:#0289cd'>".$res[0][2] . "</b><br><br>";
							echo $res[0][4];
					?>
						<br>
						</td>
					</tr>
				</table>
			
			</td>
		</tr>
	</table>
<?
include("footer.php");
?>
	