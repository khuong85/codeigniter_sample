$(function(){
		$(".intro_more").live("click", function(){
			$("#intro_less").hide();
			$(this).removeClass("intro_more").addClass("intro_less").text("Show Less");
			$("#intro_more").show();
		});
		$(".intro_less").live("click", function(){
			$("#intro_less").show();
			$(this).removeClass("intro_less").addClass("intro_more").text("Show More");
			$("#intro_more").hide();
		});

		$(".skill_more").live("click", function(){
			$("#skill_less").hide();
			$(this).removeClass("skill_more").addClass("skill_less").text("Show Less");
			$("#skill_more").show();
		});
		$(".skill_less").live("click", function(){
			$("#skill_less").show();
			$(this).removeClass("skill_less").addClass("skill_more").text("Show More");
			$("#skill_more").hide();
		});
	});