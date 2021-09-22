<?php require_once('includes/top.php'); ?>
<?php if($appStatus == 0){echo "<meta http-equiv=\"refresh\" content=\"0;url=maintenance.php\" />";exit;} ?>
	<form method="post" action="prorata.php">
	    
	    <div class="row">
	    	<div class="twelve columns">
			<label for="CattleFeedType">Select Feed Type</label>
			<select class="u-full-width" id="CattleFeedType" name="FeedType">
			<optgroup label="Cattle Feeds"></optgroup>			        	
		        	<option value="CF-01 Calf Starter Feed">CF-01 Calf Starter Feed</option>
		        	<option value="CF-02 Breeding Bull Feed">CF-02 Breeding Bull Feed</option>
		        	<option value="CF-03 Cattle Feed Type-I">CF-03 Cattle Feed Type-I</option>
		        	<option value="CF-04 Cattle Feed Type-II">CF-04 Cattle Feed Type-II</option>
		        <optgroup label="Pig Feeds"></optgroup>
		        	<option value="PGF-01 Pig Starter Feed">PGF-01 Pig Starter Feed</option>
		        	<option value="PGF-02 Breeding Pig Feed">PGF-02 Breeding Pig Feed</option>
		        	<option value="PGF-03 Lactating Pig Feed">PGF-03 Lactating Pig Feed</option>
		        <optgroup label="Poultry Feeds"></optgroup>
		        	<option value="PF-01 Broiler Starter Feed">PF-01 Broiler Starter Feed</option>
		        	<option value="PF-02 Broiler Finisher Feed">PF-02 Broiler Finisher Feed</option>
		        	<option value="PF-03 Breeder Layer Feed">PF-03 Breeder Layer Feed</option>
		        	<option value="PF-04 Breeder Chick Feed">PF-04 Breeder Chick Feed</option>
		        	<option value="PF-05 Breeder Grower Feed">PF-05 Breeder Grower Feed</option>			        	
		        <optgroup label="Rabbit Feeds"></optgroup>
		        	<option value="RF-01 Rabbit Feed">RF-01 Rabbit Feed</option>
		        <optgroup label="Sheep/Goat Feeds"></optgroup>
		        	<option value="SF-01 Sheep & Goat Feed">SF-01 Sheep & Goat Feed</option>			        
			</select>
		    </div>

		    <div class="align-center twelve columns" style="color: #cacaca;">
		  		<p>By continuing, I the user, agree to use this utility <u>at my own risk</u>.</p>
		  	</div>
	  	
		  	<div class="align-center twelve columns">
		  		<input class="button-primary" value="Continue" type="submit" name="step1">
		  	</div>
		</div>
	</form>

	<div class="row align-center">
		<br /><br /><br /><hr /><?php echo $pageTitle; ?>
	</div>

<?php require_once('includes/bottom.php'); ?>
