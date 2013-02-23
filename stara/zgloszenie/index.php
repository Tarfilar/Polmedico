<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<style type="text/css">
<!--
a:link, a:active, a:visited {color: #0057b9;text-decoration: none;}
a:hover {color: #0057b9; text-decoration: underline;}
td {
	vertical-align: top;
}

body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #5e5d59;
	margin: 0px;
	padding: 0px;
	background-color: #CCC;
}
.zaznacz {
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	width: 20px;
	margin: 0px;
	padding: 0px;
}
.but {
	background-color: #EE782E;
	font-weight: bold;
	color: #FFF;
	width: 200px;
	float: right;
	border: 1px solid #772416;
}
input {
	background-color: #FFF;
	border: 1px solid #999;
	padding: 3px;
	margin-bottom: 5px;
	width: 250px;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #666;
}
-->
</style>
<title>Formularz zgłoszeniowy uczestnictwa w konferencji</title></head>

<body>
<table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="266"><table width="266" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="img2.jpg" width="263" height="432" border="0"></td>
      </tr>
      <tr>
        <td style="font-size:13px; padding: 0px 18px 0px 18px"><p><strong>Dodatkową informację można uzyskać pod numeremi:</strong><br>
          504&nbsp;095&nbsp;905<br>
          601&nbsp;614&nbsp;514</p>
          <p><strong>Koszt szkolenia przy zgłoszeniu:</strong><br>
            do dnia 20-06-11 -&nbsp;350 zł, <br>
            po 20-06-11 &ndash; 400 zł, </p>
          <p><strong>Wpłaty prosimy dokonywać na konto:</strong></p>
          <p>BNP Paribas - Fortis <br>
            86 1600 1299 0002 3507 6646 70<br>
            OLSSEN Sp. z o.o.Ul. Sempołowskiej 16/543-300 Bielsko-Biała</p>
          <p><strong>Organizatorzy:</strong><br>
          </p></td>
      </tr>
      <tr>
        <td><img src="org.jpg" width="236" height="163"></td>
      </tr>
    </table></td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="font-size:20px; color:#e46d05; padding: 15px 0px 15px 0px"><strong>Formularz zgłoszenia</strong></td>
      </tr>
      <tr>
        <td style="font-size:13px; padding: 0px 18px 0px 0px;">
        
        
        
        
        <?php

	function echo_html($string)	{
	  echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
	}

	function is_empty($string) {
		if (strlen($string) == 0) {
			return TRUE;
		}
		return FALSE;
	}

	function valid_email($str)
	{
		return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
	}

	$data = array();
	$data['name'] = '';
	$data['surname'] = '';
	$data['invoice_name'] = '';
	$data['town'] = '';
	$data['address'] = '';
	$data['postcode'] = '';
	$data['nip'] = '';
	$data['phone'] = '';
	$data['email'] = '';
	$data['job_number'] = "";
	$data['check'] = -1;

	$validation_errors = array();
	$mail_send = FALSE;

	if (count($_POST) > 0) {
		//var_dump($_POST);

		foreach ($_POST as $key => $value) {
			$_POST[$key] = trim($value);
			$data[$key] = $_POST[$key];
		}

		//echo '<pre>';
		//print_r($data);
		//echo '</pre>';

		//walidacja
		if (is_empty($data['name'])) {
			$validation_errors['name'] = 'Pole imię musi zostać wypełnione';
		}
		if (is_empty($data['surname'])) {
			$validation_errors['surname'] = 'Pole nazwisko musi zostać wypełnione';
		}
		if (is_empty($data['town'])) {
			$validation_errors['town'] = 'Pole miejscowość musi zostać wypełnione';
		}
		if (is_empty($data['address'])) {
			$validation_errors['address'] = 'Pole adres musi zostać wypełnione';
		}
		if (is_empty($data['postcode'])) {
			$validation_errors['postcode'] = 'Pole kod pocztowy musi zostać wypełnione';
		}
		if (is_empty($data['nip'])) {
			$validation_errors['nip'] = 'Pole NIP musi zostać wypełnione';
		}
		if (is_empty($data['phone'])) {
			$validation_errors['phone'] = 'Pole telefon musi zostać wypełnione';
		}
		if (!valid_email($data['email'])) {
			$validation_errors['email'] = 'Pole email musi zawierać poprawny adres email';
		}

		if ($data['check'] == -1) {
			$validation_errors['check'] = 'Nie zaznaczono akceptacji warunków zgłoszenia.';
		}

		if (count($validation_errors) == 0) {
			//echo 'wysylam maila';
			$mail_send = TRUE;

			$to      = 'info@polmedico.com';
			$subject = 'zgłoszenie uczestnictwa w konferencji';
			$message = 'Imie: '.$data['name']."\r\n";
			$message .= 'Nazwisko: '.$data['surname']."\r\n";
			$message .= 'Nazwa do faktury VAT: '.$data['invoice_name']."\r\n";
			$message .= 'Miejscowość: '.$data['town']."\r\n";
			$message .= 'Adres: '.$data['address']."\r\n";
			$message .= 'Kod pocztowy: '.$data['postcode']."\r\n";
			$message .= 'NIP: '.$data['nip']."\r\n";
			$message .= 'Telefon: '.$data['phone']."\r\n";
			$message .= 'Email: '.$data['email']."\r\n";
			$message .= 'Numer prawa wykonywania zawodu: '.$data['job_number']."\r\n";

			$headers = 'From: '.$data['email']. "\r\n" .
			    'Reply-To: '.$data['email']. "\r\n" .
			    'X-Mailer: PHP/' . phpversion();

			if (mail($to, $subject, $message, $headers)) {
				echo 'Dziękujemy. Twoje zgłoszenie zostało przyjęte.';
			} else {
				echo 'Wystąpił błąd w trakcie przyjmowania Twojego zgłoszenie. Nie zostało ono przyjęte. Przepraszamy.';
			}

		}
	}
?>

<?php if (!$mail_send): ?>

		<?php foreach ($validation_errors as $error): ?>
			<?php echo $error; ?><br/>
		<?php endforeach; ?>

		<form method="post">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr><td width="50%">Imię *</td>
			<td width="50%"><input name="name" value="<?php echo_html($data['name']); ?>" ></td></tr>
			<tr><td>Nazwisko *</td>
			<td><input name="surname" value="<?php echo_html($data['surname']); ?>" ></td></tr>
			<tr><td>Nazwa do faktury VAT</td>
			<td><input name="invoice_name" value="<?php echo_html($data['invoice_name']); ?>" ></td></tr>
			<tr><td>Miejscowość *</td>
			<td><input name="town" value="<?php echo_html($data['town']); ?>" ></td></tr>
			<tr><td>Adres (ulica, nr domu/ mieszkania) *</td>
			<td><input name="address" value="<?php echo_html($data['address']); ?>" ></td></tr>
			<tr><td>Kod pocztowy *</td>
			<td><input name="postcode" value="<?php echo_html($data['postcode']); ?>" ></td></tr>
			<tr><td>NIP *</td>
			<td><input name="nip" value="<?php echo_html($data['nip']); ?>" ></td></tr>
			<tr>
			  <td>Telefon *</td>
			<td><input name="phone" value="<?php echo_html($data['phone']); ?>" ></td></tr>
			<tr>
			  <td>E-mail *</td>
			<td><input name="email" value="<?php echo_html($data['email']); ?>" ></td></tr>
			<tr><td>Numer prawa wykonywania zawodu</td>
			<td><input name="job_number" value="<?php echo_html($data['job_number']); ?>" ></td></tr>
		</table>
		<input class="zaznacz" type="checkbox" name="check" value="1"
			<?php if ($data['check'] == 1): ?>
			checked="checked"
			<?php endif; ?>
		/>
		zgłaszam chęć wzięcia udziału w konferencji naukowo - szkoleniowej nt. "NOWE OSIĄGNIĘCIA MEDYCYNY ZAPOBIEGAWCZEJ - OD ANTY DO HEALTHY AGING", koszt szkolenia przy zgłoszeniu:
do dnia 20-06-11 - 350 zł, po 20-06-11 - 400 zł. *<br/>
		<br/>
		<input type="submit" name="submit" value="Wyślij swoje zgłoszenie" class="but" />
		</form>
<?php endif; ?>
        
        
        
        
        
        
        </td>
       </tr>
      <tr>
        <td style="font-size:20px; color:#e46d05; padding: 35px 0px 15px 0px"><strong>Jedną z najistotniejszych trosk wsp&oacute;łczesnego człowieka jest starzenie się w dobrym zdrowiu i komforcie psychicznym&hellip;</strong></td>
      </tr>
      <tr>
        <td style="font-size:13px; padding: 0px 18px 0px 0px"><p>Poliklinika &bdquo;Pod Szyndzielnią&rdquo; oraz Ośrodek Medycyny Zapobiegawczej Olssen - polski partner&nbsp;Francuskiego Stowarzyszenia Lekarzy na rzecz Medycyny Zapobiegawczej MEDIPREVENT z siedzibą w Paryżu zapraszają do wzięcia udziału w konferencji naukowo-szkoleniowej nt:<br>
            <strong><br>
          &bdquo;NOWE OSIĄGNIĘCIA MEDYCYNY ZAPOBIEGAWCZEJ - OD ANTY DO HEALTHY AGING&rdquo;</strong></p>
          <p>Konferencja odbędzie się: <strong>2 lipca 2011 </strong><br>
            w Poliklinice Stomatologicznej &bdquo;Pod Szyndzielnią&rdquo; w Bielsku-Białej, ul.Armii Krajowej 193.</p>
          <p>Patronat naukowy: Wydział Opieki Zdrowotnej Wyższej Szkoły Nauk Stosowanych w Rudzie Śląskiej i Francuskie Stowarzyszenie Lekarzy na Rzecz Medycyny Zapobiegawczej MEDIPREVENT w Paryżu.</p>
          <p><strong>Program:</strong></p>
          <ul>
            <li><strong>Profilaktyczne Bilanse Biologiczne &ndash; nowa jakość w medycynie zdrowego starzenia</strong><br>
              lek. med. DanielaKurczabińska &ndash; Luboń &ndash; Ośrodek Medycyny Zapobiegawczej NZOZ Olssen, Katowice<br>
              <br>
            </li>
            <li><strong>Kwasy tłuszczowe i stres oksydacyjny w kontekście procesu starzenia</strong><br>
              dr Marc Peignier -&nbsp;członek Rady Naukowej Mediprevent, Klinika La Corbiere,  Szwajcaria.<br>
              <br>
            </li>
            <li><strong>Optymalizacja mechanizm&oacute;w epigenetycznych</strong><br>
              dr Marc Peignier -&nbsp;członek Rady Naukowej Mediprevent, Klinika La Corbiere,  Szwajcaria.<br>
              <br>
            </li>
            <li><strong>Stomatologia przeciwstarzeniowa</strong><br>
              dr n. med Katarzyna Becker &ndash; Klinika pod Szyndzielnią, Bielsko-Biała<br>
              <br>
            </li>
            <li><strong>Obraz własnego ciała jako wyznacznik psychosomatycznego dobrostanu</strong><br>
              dr n.hum. Monika Bąk-Sosnowska, Zakład Psychologii Śląskiego Uniwersytetu Medycznego i Gabinet Terapeutyczno-Szkoleniowy               PRIMODIUM&nbsp;w Katowicach<br>
              <br>
            </li>
            <li><strong>Rola probiotyk&oacute;w oraz celowanej suplementacji w zapobieganiu mechanizmom starzenia kom&oacute;rkowego</strong><br>
              Lek med. Katarzyna Rajter-Grabowska  - Laboratorium Pileje<br>
              <br>
            </li>
            <li><strong>Oleje 10 stopniowe firmy Złoto Polskie - wpływ produkcji i dystrybucji na
jakość produktu</strong><br>
mgr farmacji Maria Jankowska, Oleje Złotopolskie</li>
          </ul>
          <p><strong><br>
            Ramowy czas spotkania:</strong><br>
            9.00 - rejestracja uczestnik&oacute;w<br>
            9.30-15.00 - konferencja</p>
          <p><strong>Uczestnicy szkolenia otrzymują certyfikat.</strong></p>
          <p>Sponsorzy: Poliklinika &bdquo;Pod Szyndzielnią&rdquo;, Dr Katarzyna Rajter-Grabowska - Medikatha Sp. z o.o. wyłączny dystrybutor
Pileje, Ustronianka Sp. z o.o., Złoto Polskie Marek Wolniak<br>
          <br>
          <strong>Serdecznie zapraszamy!</strong><br>
          <br>
          <br>
          <br>
          <br>
          </p></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
