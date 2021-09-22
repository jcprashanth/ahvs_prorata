<?php require_once('includes/top.php'); ?>
<?php if($appStatus == 0){echo "<meta http-equiv=\"refresh\" content=\"0;url=maintenance.php\" />";exit;} ?>

<?php

if(!isset($_POST['FeedType'])){echo "<meta http-equiv=\"refresh\" content=\"0;url=./\" />";exit;}

$http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'? "https://" : "http://";
$url = $http . $_SERVER["SERVER_NAME"];

function convertNumberToWord($num = false)
{
    $num = str_replace(array(',', ' '), '' , trim($num));
    if(! $num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
        'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    );
    $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
    $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
        'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
        'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ( $tens < 20 ) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
        } else {
            $tens = (int)($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}

$FeedType = $_POST['FeedType'];

// Setting Standard values for feed types
if($FeedType == "PF-01 Broiler Starter Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "5";
	$sprotein = "23";
}

if($FeedType == "PF-02 Broiler Finisher Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "5";
	$sprotein = "20";
}

if($FeedType == "PF-03 Breeder Layer Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "5";
	$sprotein = "17";
}

if($FeedType == "PF-04 Breeder Chick Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "5";
	$sprotein = "20";
}

if($FeedType == "PF-05 Breeder Grower Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "5";
	$sprotein = "16";
}

if($FeedType == "CF-01 Calf Starter Feed"){
	$smoisture = "11";
	$sash = "2.5";
	$sfat = "4";
	$sfibre = "7";
	$sprotein = "23";
}

if($FeedType == "CF-02 Breeding Bull Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "2.5";
	$sfibre = "8";
	$sprotein = "18";
}

if($FeedType == "CF-03 Cattle Feed Type-I"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "4";
	$sfibre = "10";
	$sprotein = "22";
}

if($FeedType == "CF-04 Cattle Feed Type-II"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "2.5";
	$sfibre = "12";
	$sprotein = "20";
}

if($FeedType == "SF-01 Sheep & Goat Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "2.5";
	$sfibre = "12";
	$sprotein = "20";
}

if($FeedType == "PGF-01 Pig Starter Feed"){
	$smoisture = "11";
	$sash = "4";
	$sfat = "2";
	$sfibre = "5";
	$sprotein = "20";
}

if($FeedType == "PGF-02 Breeding Pig Feed"){
	$smoisture = "11";
	$sash = "4";
	$sfat = "2";
	$sfibre = "6";
	$sprotein = "16";
}

if($FeedType == "PGF-03 Lactating Pig Feed"){
	$smoisture = "11";
	$sash = "4";
	$sfat = "2";
	$sfibre = "8";
	$sprotein = "16";
}

if($FeedType == "RF-01 Rabbit Feed"){
	$smoisture = "11";
	$sash = "3";
	$sfat = "3";
	$sfibre = "12";
	$sprotein = "16";
}

$process = "no";
$errorSupplierName = 'no';
$errorInvoiceNumber = 'no';
$errorInvoiceDate = 'no';
$errorQuantitySupplied = 'no';
$errorCostPerTonne = 'no';
$errorLabCharges = 'no';
$errorlmoisture = 'no';
$errorlash = 'no';
$errorlfat = 'no';
$errorlfibre = 'no';
$errorlprotein = 'no';

if(isset($_POST['step2'])){

$process = 'yes';

$SupplierName = ""; $SupplierName = $_POST['SupplierName'];
$InvoiceNumber = ""; $InvoiceNumber = $_POST['InvoiceNumber'];
$InvoiceDate = ""; $InvoiceDate = $_POST['InvoiceDate'];
$QuantitySupplied = ""; $QuantitySupplied = $_POST['QuantitySupplied'];
$CostPerTonne = ""; $CostPerTonne = $_POST['CostPerTonne'];
$LabCharges = ""; $LabCharges = $_POST['LabCharges'];
$lmoisture = ""; $lmoisture = $_POST['lmoisture'];
$lash = ""; $lash = $_POST['lash'];
$lfat = ""; $lfat = $_POST['lfat'];
$lfibre = ""; $lfibre = $_POST['lfibre'];
$lprotein = ""; $lprotein = $_POST['lprotein'];

if($SupplierName == ""){$errorSupplierName = 'yes'; $process = 'no';}
if($InvoiceNumber == ""){$errorInvoiceNumber = 'yes'; $process = 'no';}
if($InvoiceDate == ""){$errorInvoiceDate = 'yes'; $process = 'no';}
if($QuantitySupplied == "" || !preg_match("/^[1-9][0-9]*$/", $QuantitySupplied)){$errorQuantitySupplied = 'yes'; $process = 'no';}
if($CostPerTonne == "" || !is_numeric($CostPerTonne)){$errorCostPerTonne = 'yes'; $process = 'no';}
if($LabCharges == "" || !is_numeric($LabCharges)){$errorLabCharges = 'yes'; $process = 'no';}
if($lmoisture == "" || !is_numeric($lmoisture)){$errorlmoisture = 'yes'; $process = 'no';}
if($lash == "" || !is_numeric($lash)){$errorlash = 'yes'; $process = 'no';}
if($lfat == "" || !is_numeric($lfat)){$errorlfat = 'yes'; $process = 'no';}
if($lfibre == "" || !is_numeric($lfibre)){$errorlfibre = 'yes'; $process = 'no';}
if($lprotein == "" || !is_numeric($lprotein)){$errorlprotein = 'yes'; $process = 'no';}

}

if($process == "yes"){

?>

<div class="row">
	<div class="twelve columns">
		<p>Prorata calculation of <?php echo $FeedType; ?> supplied by M/s <?php echo $SupplierName; ?> vide Invoice No. <?php echo $InvoiceNumber; ?>, dated <?php echo $InvoiceDate; ?>.</p>
	</div>
</div>

<div class="row">
	<div class="two columns">&nbsp;</div>
	<div class="ten columns">
		<p>
			Feed Type: <?php echo $FeedType; ?>
			<br />
			Quantity Supplied: <?php echo $QuantitySupplied; ?> Kgs
			<br />
			Cost per Tonne: Rs. <?php echo $CostPerTonne; ?>
			<br />
			Calculated Bill Amount: Rs. <?php echo $BillAmount = ($QuantitySupplied*$CostPerTonne)/1000; ?>
		</p>
	</div>
</div>

<div class="row">
	<div class="twelve columns">

		<strong>A. Calculating Actual Weight</strong><br />
		<div style="overflow-x:auto;">
			<table>
				<tr>
					<th>Sl. No.</th>
					<th>Ingredient</th>
					<th>Standard Value</th>
					<th>Feed Analysis Value</th>
					<th>Difference Value</th>
				</tr>				
				<tr>
					<td>1</td>
					<td>Moisture</td>
					<td><?php echo $smoisture; ?></td>
					<td><?php echo $lmoisture; ?></td>
					<td><?php $dmoisture = round($lmoisture - $smoisture,2); if($dmoisture <= 0){$dmoisture = 0;} echo $dmoisture; ?></td>
				</tr>
				<tr>
					<td>2</td>
					<td>Crude Fibre</td>
					<td><?php echo $sfibre; ?></td>
					<td><?php echo $lfibre; ?></td>
					<td><?php $dfibre = round($lfibre - $sfibre,2); if($dfibre <= 0){$dfibre = 0;} echo $dfibre; ?></td>
				</tr>
				<tr>
					<td>3</td>
					<td>Acid Insol. Ash</td>
					<td><?php echo $sash; ?></td>
					<td><?php echo $lash; ?></td>
					<td><?php $dash = round($lash - $sash,2); if($dash <= 0){$dash = 0;} echo $dash; ?></td>
				</tr>
				<tr>
					<td colspan="4">Total Difference</td>
					<td><?php $tdiff1 = round($dmoisture + $dfibre + $dash, 2); echo $tdiff1; ?></td>
				</tr>
			</table>
		</div>

	</div>
</div>

<div class="row">
	<div class="twelve columns">

		Difference in Weight = ( <?php echo $QuantitySupplied; ?> x <?php echo $tdiff1; ?> ) / 100  = <?php $WtDiff = round(($QuantitySupplied*$tdiff1)/100,2); echo $WtDiff; ?> Kg
		<br />
		Actual Weight = <?php echo $QuantitySupplied; ?> - <?php echo $WtDiff; ?> = <strong><?php echo $actualWeight = $QuantitySupplied - $WtDiff; ?> Kg</strong>

	</div>
</div>

<div class="row">
	<div class="twelve columns">

		<br />
		<strong>B. Calculating Actual Cost</strong>
		<br />
		Converstion: 1% Crude Protein = 1 Unit &amp; 0.25% Crude Fat = 1 Unit.
		<div style="overflow-x:auto;">
			<table>
				<tr>
					<th>Sl. No.</th>
					<th>Ingredient</th>
					<th>Standard Unit</th>
					<th>Feed Analysis Value</th>
					<th>Feed Analysis Unit</th>
					<th>Considered Unit</th>
				</tr>				
				<tr>
					<td>1</td>
					<td>Crude Protein</td>
					<td><?php echo $sprotein; ?></td>
					<td><?php echo $lprotein; ?></td>
					<td><?php echo $lprotein; ?></td>
					<td><?php $cprotein = $sprotein; if($lprotein < $sprotein){$cprotein = $lprotein;} echo $cprotein; ?></td>
				</tr>
				<tr>
					<td>2</td>
					<td>Crude Fat</td>
					<td><?php $sfat = round($sfat*4,2); echo $sfat; ?></td>
					<td><?php echo $lfat; ?></td>
					<td><?php $fatu = round($lfat*4,2); echo $fatu; ?></td>
					<td><?php $cfat = $sfat; if($fatu < $sfat){$cfat = $fatu;} echo $cfat; ?></td>
				</tr>
				<tr>
					<td colspan="2">Total</td>
					<td><?php $tsu = round($sprotein + $sfat, 2); echo $tsu; ?></td>
					<td colspan="2">Total</td>
					<td><?php $tlu = round($cprotein + $cfat, 2); echo $tlu; ?></td>
				</tr>
			</table>
		</div>

	</div>
</div>

<div class="row">
	<div class="twelve columns">

		Cost per Tonne = ( <?php echo $tlu; ?> x <?php echo $CostPerTonne; ?> ) / <?php echo $tsu; ?>  = <strong>Rs. <?php echo $calcCost = round(($tlu*$CostPerTonne)/$tsu,2); ?></strong>
		<br />
		Payable Amount = ( <?php echo $actualWeight; ?> x <?php echo $calcCost; ?> ) / 1000 = <strong>Rs. <?php echo $payAmount = round(($calcCost*$actualWeight)/1000,0); ?></strong>
		<br />
		Difference Amount = ( Bill Amount - Payable Amount )
		<br />
		Difference Amount = <?php echo $BillAmount; ?> - <?php echo $payAmount; ?> = <strong>Rs. <?php echo $diffAmount = $BillAmount-$payAmount; ?></strong>

	</div>
</div>

<div class="row">
	<div class="twelve columns">

		<br />
		<strong>C. Calculating Total Deduction</strong>
		<div style="overflow-x:auto;">
			<table>
				<tr>
					<th>Sl. No.</th>
					<th>Description</th>
					<th>Amount in Rs.</th>
				</tr>				
				<tr>
					<td>1</td>
					<td style="text-align: left;"><?php echo $FeedType; ?> prorata deduction</td>
					<td style="text-align: right;"><?php echo $diffAmount; ?></td>
				</tr>
				<tr>
					<td>2</td>
					<td style="text-align: left;">Feed Analysis Charges</td>
					<td style="text-align: right;"><?php echo $LabCharges; ?></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right; font-weight: bold">Total Deduction</td>
					<td style="text-align: right; font-weight: bold"><?php echo $totalDeduction = $diffAmount + $LabCharges; ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="row">
	<div class="twelve columns align-center">

		<strong>
			Total Amount to be Deducted is<br />
			Rupees <?php echo ucwords(convertNumberToWord($totalDeduction)); ?> Only			
		</strong>

	</div>

	<div class="twelve columns align-center hide-me"><br /><br /><a href="./" class="button">Home</a> &nbsp; &nbsp; &nbsp; <button onclick="myFunction()" class="button-primary">Print</button></div>
</div>

<div class="row">
	<div class="twelve columns print-only">		
		[ Generated at <?php echo $url; ?> ]
	</div>
</div>

 
<?php }else{ ?>

	<?php if(isset($_POST['step2']) && $process == 'no'){ ?>
		<div class="row">
			<div class="twelve columns inputError">Input Error! Please correct the fields highlighted in red.</div>
		</div>
	<?php } ?>

	<form method="post">

		<div class="row">

		    <div class="six columns">
				<label for="FeedType">Feed Type</label>
				<input class="u-full-width" id="FeedType" type="text" readonly="readonly" value="<?php echo $FeedType; ?>" name="FeedType">
		    </div>

		    <div class="twelve columns">
	    		&nbsp;
	    	</div>

		</div>

	    <h5>Bill Details:</h5>

	    <div class="row">
	    	
	    	<div class="four columns">
	      		<label for="SupplierName">Supplier Name</label>
	      		<input class="u-full-width" placeholder="Supplier Name" id="SupplierName" name="SupplierName" type="text"<?php if($errorSupplierName == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($SupplierName)){echo "value=\"".$SupplierName."\"";} ?>>
	    	</div>

	    	<div class="four columns">
	      		<label for="InvoiceNumber">Invoice Number</label>
	      		<input class="u-full-width" placeholder="Invoice Number" id="InvoiceNumber" name="InvoiceNumber" type="text"<?php if($errorInvoiceNumber == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($InvoiceNumber)){echo "value=\"".$InvoiceNumber."\"";} ?>>
    		</div>

	    	<div class="four columns">
	      		<label for="datepicker">Invoice Date</label>
	      		<input class="u-full-width" placeholder="DD/MM/YYYY" id="datepicker" name="InvoiceDate" type="text" readonly="readonly"<?php if($errorInvoiceDate == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($InvoiceDate)){echo "value=\"".$InvoiceDate."\"";} ?>>
	    	</div>

	    	<div class="twelve columns">
	    		&nbsp;
	    	</div>

	    </div>

	    <div class="row">
	    	
	    	<div class="four columns">
	      		<label for="QuantitySupplied">Quantity Supplied (Kgs)</label>
	      		<input class="u-full-width" placeholder="Quantity in Kgs" id="QuantitySupplied" name="QuantitySupplied" type="text"<?php if($errorQuantitySupplied == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($QuantitySupplied)){echo "value=\"".$QuantitySupplied."\"";} ?>>
	    	</div>

	    	<div class="four columns">
	      		<label for="CostPerTonne">Cost/Tonne (Rs)</label>
	      		<input class="u-full-width" placeholder="Cost/Tonne in Rs" id="CostPerTonne" name="CostPerTonne" type="text"<?php if($errorCostPerTonne == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($CostPerTonne)){echo "value=\"".$CostPerTonne."\"";} ?>>
	    	</div>

	    	<div class="four columns">
	      		<label for="LabCharges">Lab Analysis Charge (Rs)</label>
	      		<input class="u-full-width" placeholder="Charges in Rs" id="LabCharges" name="LabCharges" type="text"<?php if($errorLabCharges == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($LabCharges)){echo "value=\"".$LabCharges."\"";} ?>>
    		</div>

    		<div class="twelve columns">
	    		&nbsp;
	    	</div>

	    </div>

	    <h5>Lab Report Details:</h5>

	    <div style="overflow-x:auto;">
		    <table class="u-full-width">
				<thead>
					<tr>
						<th>Ingredient</th>
						<th>Standard Value</th>
						<th>Lab Report Value</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Moisture</td>
						<td><input size="2" name="smoisture" type="text" value="<?php echo $smoisture; ?>" readonly="readonly"></td>
						<td><input size="2" placeholder="% DM" name="lmoisture" type="text"<?php if($errorlmoisture == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($lmoisture)){echo "value=\"".$lmoisture."\"";} ?>></td>
					</tr>
					<tr>
						<td>Acid Insoluble Ash</td>
						<td><input size="2" name="sash" type="text" value="<?php echo $sash; ?>" readonly="readonly"></td>
						<td><input size="2" placeholder="% DM" name="lash" type="text"<?php if($errorlash == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($lash)){echo "value=\"".$lash."\"";} ?>></td>
					</tr>
					<tr>
						<td>Crude Fat</td>
						<td><input size="2" name="sfat" type="text" value="<?php echo $sfat; ?>" readonly="readonly"></td>
						<td><input size="2" placeholder="% DM" name="lfat" type="text"<?php if($errorlfat == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($lfat)){echo "value=\"".$lfat."\"";} ?>></td>
					</tr>
					<tr>
						<td>Crude Fibre</td>
						<td><input size="2" name="sfibre" type="text" value="<?php echo $sfibre; ?>" readonly="readonly"></td>
						<td><input size="2" placeholder="% DM" name="lfibre" type="text"<?php if($errorlfibre == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($lfibre)){echo "value=\"".$lfibre."\"";} ?>></td>
					</tr>
					<tr>
						<td>Crude Protein</td>
						<td><input size="2" name="sprotein" type="text" value="<?php echo $sprotein; ?>" readonly="readonly"></td>
						<td><input size="2" placeholder="% DM" name="lprotein" type="text"<?php if($errorlprotein == 'yes'){echo "style=\"border: 1px solid red\"";} if(isset($lprotein)){echo "value=\"".$lprotein."\"";} ?>></td>
					</tr>
				</tbody>
			</table>
		</div>

	    <div class="row align-center">
	    	<div class="twelve columns">
	    		&nbsp;
	    	</div>
	    	<div class="twelve columns" style="color: brown; font-weight: bold;">
	    		Please verify the feed type, standard values &amp; lab report values before proceeding.
	    	</div>
	    	<div class="twelve columns">
	    		&nbsp;
	    	</div>
	    	<div class="twelve columns">
	    		<a href="./" class="button">Back</a> &nbsp; &nbsp; &nbsp; <input class="button-primary" value="Calculate" type="submit" name="step2">
	    	</div>
	  	</div>
	</form>

	<div class="row align-center">
		<br /><br /><br /><hr /><?php echo $pageTitle; ?>
	</div>

<?php } ?>

<?php require_once('includes/bottom.php'); ?>
