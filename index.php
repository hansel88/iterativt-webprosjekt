<?php
 include 'php/indexHeader.php';
 ?>
		<section id="wrapper">
			<h1>Rom-booking</h1>

			<!--<div id="form">-->
						<!--
						Dato: <input type="date" name="dato" required>
						<br>
						Fra <input type="time" name="ftid" required>
						til <input type="time" name="ttid" required> 
						-->
				<form class="pure-form pure-form-aligned" id="searchForm" name="searchForm" action="php/search.php" method="post">

					<div class="pure-control-group">
						<label for="date">Dato:</label>
						<input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"required>
					</div>

					<div class="pure-control-group">
						<label for="hours">Antall timer: </label>
						<select id="hours" name="hours">
						  <option value="1">1</option>
						  <option value="2">2</option>
						  <option value="3">3</option>
						  <option value="4">4</option>
						  <option value="5">5</option>
						</select>
					</div>

					<div class="pure-control-group">
						<label for="size">Antall personer: </label>
						<select id="size">
						  <option value="2">2</option>
						  <option value="3">3</option>
						  <option value="4">4</option>
						</select>
					</div>

					<div class="pure-controls">
						<label for="projector" class="pure-checkbox">
							<input type="checkbox" name="projector" value="yes"> Rom med projektor
						</label>

						<button type="submit" class="pure-button pure-button-primary">Søk rom</button>
					</div>
					
				</form>
			<!--</div>-->
		</section>
	<script src="js/jquery.js">
	</script>
	<script src="js/jquery.datetimepicker.js">
	</script>
	<script src="js/datetimeconfig.js">
	</script>
	<script src="js/misc.js">
	</script>

	 <?php include 'php/footer.php'; ?>

<!-- Gruppe 27 -->