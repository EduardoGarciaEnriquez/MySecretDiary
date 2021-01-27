	    <!-- Optional JavaScript -->
	    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	    <script type="text/javascript">
	    	//function to switch (toggle) between log in and sign up.
	    	$(".toggleForm").click(function() {
	    		$("#SignUpForm").toggle();
	    		$("#LogInForm").toggle();
	    	});

	    	//detect change in the #daryText (text area)
	    	$('#diaryText').bind('input propertychange', function() {
	    		//alert("changed");
	    		//ajax function, everything from method _POST
	    		$.ajax({
					  method: "POST",
					  url: "updateDB.php",
					  data: { content: $("#diaryText").val() }
				})
			});

	    </script>
	</body>
</html>