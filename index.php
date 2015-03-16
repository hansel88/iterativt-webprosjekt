<?php
 include 'php/indexHeader.php';
 ?>
		<section id="wrapper">
			<h1>Rom-booking</h1>

			<div id="form">
				<form class="pure-form pure-form-aligned" id="searchForm" name="searchForm" action="php/search.php" method="post">
					<fieldset>
						<div class="pure-control-group">
							<label for="email">Epost:</label>
							<input type="email" name="email" size="25" pattern=".+@student.westerdals.no" title="@student.westerdals.no" placeholder="bruker@student.westerdals.no" required>
						</div>
						<!--
						Dato: <input type="date" name="dato" required>
						<br>
						Fra <input type="time" name="ftid" required>
						til <input type="time" name="ttid" required>
						-->

						<div class="pure-control-group1">
							<label for="some_class_1">Fra:</label>
							<input type="text" class="some_class" name="fromDate" value="" id="fromDate" size="25" required>
							<label for="some_class_2">Til:</label>
							<input type="text" class="some_class" name="toDate" value="" id="toDate" size="25" required>
						</div>

						<div class="pure-control-group2">
							<label for="size">Antall personer: </label>
							<input type="radio" name="size" value="2" required>
							2
							<input type="radio" name="size" value="3">
							3
							<input type="radio" name="size" value="4">
							4
						</div>

						<div class="pure-controls">
							<label for="projector" class="pure-checkbox">
								<input type="checkbox" name="projector" value="yes"> Rom med projektor
							</label>

							<button type="submit" class="pure-button pure-button-primary">Søk rom</button>
						</div>
					</fieldset>
				</form>
			</div>
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