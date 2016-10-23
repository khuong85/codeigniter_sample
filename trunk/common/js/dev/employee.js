/**
 * Utilities methods for employee (my profile screen)
 * @author Le Toan <le.toan@mulodo.com>
 * @name employee.js
 *
 */

$(document).ready(function() {

	/*
	 * Utilities functions  for getCookie
	 * */
	if ( typeof String.prototype.trimLeft !== "function") {
		String.prototype.trimLeft = function() {
			return this.replace(/^\s+/, "");
		};
	}
	if ( typeof String.prototype.trimRight !== "function") {
		String.prototype.trimRight = function() {
			return this.replace(/\s+$/, "");
		};
	}
	if ( typeof Array.prototype.map !== "function") {
		Array.prototype.map = function(callback, thisArg) {
			for (var i = 0, n = this.length, a = []; i < n; i++) {
				if ( i in this)
					a[i] = callback.call(thisArg, this[i]);
			}
			return a;
		};
	}
	function getCookies() {
		var c = document.cookie, v = 0, cookies = {};
		if (document.cookie.match(/^\s*\$Version=(?:"1"|1);\s*(.*)/)) {
			c = RegExp.$1;
			v = 1;
		}
		if (v === 0) {
			c.split(/[,;]/).map(function(cookie) {
				var parts = cookie.split(/=/, 2), name = decodeURIComponent(parts[0].trimLeft()), value = parts.length > 1 ? decodeURIComponent(parts[1].trimRight()) : null;
				cookies[name] = value;
			});
		} else {
			c.match(/(?:^|\s+)([!#$%&'*+\-.0-9A-Z^`a-z|~]+)=([!#$%&'*+\-.0-9A-Z^`a-z|~]*|"(?:[\x20-\x7E\x80\xFF]|\\[\x00-\x7F])*")(?=\s*[,;]|$)/g).map(function($0, $1) {
				var name = $0, value = $1.charAt(0) === '"' ? $1.substr(1, -1).replace(/\\(.)/g, "$1") : $1;
				cookies[name] = value;
			});
		}
		return cookies;
	}

	function getCookie(name) {
		return getCookies()[name];
	}

	//end utility function for cookie
	$.ajaxSetup({
		data : {
			csrf_test_name : getCookie('csrf_cookie_name')
		}
	});

	//handle pagination for project assignments list (ajax)
	$("#ajax_project_assignments a").live("click", function() {
		var url = $(this).attr("href");
		$.ajax({
			type : "GET",
			url : url,
			//data: ({'offset_project_assignments': offset_page}),
			async : false,
			error : function() {
				alert("There are some problem with your operation so it's failed.");
			},
			success : function(result) {
				$("#content_project_assignments").html(result);
				return false;
			}
		});
		return false;
	});

	/*
	 * Handle pagination for evaluations list (ajax)
	 *
	 */
	$("#ajax_evaluations a").live("click", function() {
		var url, offset_page = 1;
		url = $(this).attr("href");
		console.log(url);
		$.ajax({
			type : "GET",
			url : url,
			async : false,
			error : function() {
				alert("There are some problem with your operation so it's failed.");
			},
			success : function(kq) {
				$("#content_evaluations").html(kq);
				return false;
			}
		});

		return false;
	});

	//using datepicker for evaluated date in evaluation
	$("#date_evaluation").datepicker({
		dateFormat : 'dd-mm-yy'
	});

	$("#date_evaluation_edit").datepicker({
		dateFormat : 'dd-mm-yy'
	});

	/*
	 * Handle for add evaluation
	 */

	$("#add_evaluation").click(function() {
		var rank = $("#dropdown_rank_id"), date_evaluation = $("#date_evaluation"), detail = $("#details"), allFields = $([]).add(rank).add(date_evaluation).add(detail), employee_name = $(this).attr('name');

		//get ranks list from database to load into combobox with Get request
		$.ajax({
			type : "GET",
			url : CI.BASE_URL + "employee/get_list_ranks",
			async : false,
			dataType : "json",
			error : function() {
				alert("There are some problem with your operation so it's failed.");
			},
			success : function(data) {
				$('#dropdown_rank_id').empty();
				var results = data;
				// var results = JSON.parse(data);
				$.each(results, function(i, option) {
					$('#dropdown_rank_id').append($('<option/>').attr('value', option.rank_id).text(option.name));
				});
				return false;
			}
		});

		//config info for add evaluation dialog
		$("#dialog-add-evaluation").dialog({
			autoOpen : false,
			title : "Create evaluation for member: ",
			height : 500,
			width : 550,
			modal : true,
			buttons : {
				/*
				 * Handle for apply button: check if input is valid, call Post request to insert an evaluation into database
				 * then get the new evaluations list and close the dialog if success, otherwise just show the error message
				 */
				"Apply" : function() {
					tips = $(".validateTips_add_evaluation");
					var bValid = true;
					allFields.removeClass("ui-state-error");
					bValid = bValid && checkEmpty(date_evaluation, 'Evaluated date');
					bValid = bValid && checkEmpty(detail, "Detail evaluation");
					bValid = bValid && check500Character(detail, "Detail evaluation");

					if (bValid) {
						$.ajax({
							type : "POST",
							url : CI.BASE_URL + "employee/insert_evaluation_member",
							data : ( {
								'rank_id' : rank.val(),
								'date_evaluation' : date_evaluation.val(),
								'detail' : $.trim(detail.val())
							}),
							async : false,
							error : function() {
								alert("There are some problem with your operation so it's failed.");
							},
							success : function(data) {
								if (!data) {
									alert("You added the exist evaluated date for the employee!");
									//window.location.reload();
								} else {
									$("#content_evaluations").html(data);
								}
							}
						});

						$(this).dialog("close");

					}
					return false;

				},

				Cancel : function() {
					$(this).dialog("close");
				}
			},
			close : function() {
				allFields.val("").removeClass("ui-state-error");
			}
		});

		$("#dialog-add-evaluation").dialog("open");

		return false;
	});

	/*
	 * Handle for seeing more evaluation
	 */
	$("#more_evaluation").live('click', function() {

		info_evaluation = $(this).attr('value').split("_");
		var detail_info;

		//info_evaluation:
		/*
		* [0]: evaluate id
		* [1]: rank name
		* [2]: evaluated date
		* [3]: name employee
		*/

		// Get detail about evaluation for employee
		$.ajax({
			type : "GET",
			url : CI.BASE_URL + "employee/get_detail_evaluation",
			data : ( {
				'evaluate_id' : info_evaluation[0]
			}),
			async : false,
			error : function() {
				alert("There are some problem with your operation so it's failed.");
			},
			success : function(data) {
				$("#detail_evaluation").html(data);
				$("#evaluted_date").text(info_evaluation[2]);
				$("#rank").text(info_evaluation[1]);
				detail_info = data;
				return false;
			}
		});

		//config info for more evaluation dialog
		$("#dialog-more-evaluation").dialog({
			autoOpen : false,
			title : "Evaluation details: ",
			height : 500,
			width : 550,
			modal : true,
			buttons : {
				/*
				 * Handle for update button: get ranks list and select right rank for the evaluated employee and other info
				 *
				 */
				"Update" : function() {
					$(this).dialog("close");
					//open edit dialog
					$.ajax({
						type : "GET",
						url : CI.BASE_URL + "employee/get_list_ranks",
						async : false,
						error : function() {
							alert("There are some problem with your operation so it's failed.");
						},
						success : function(data) {
							$('#dropdown_rank_id_edit').empty();
							var results = JSON.parse(data);

							$.each(results, function(i, option) {
								$('#dropdown_rank_id_edit').append($('<option/>').attr('value', option.rank_id).text(option.name));
							});
							return false;
						}
					});

					$("#dropdown_rank_id_edit option:contains(" + info_evaluation[1] + ")").attr('selected', 'selected');
					$("#date_evaluation_edit").val(info_evaluation[2]);
					$("#details_edit").val(detail_info);

					$("#dialog-edit-evaluation").dialog({
						autoOpen : false,
						title : "Edit evaluation for member: ",
						height : 500,
						width : 550,
						modal : true,
						buttons : {
							/*
							 * Handle for update function: if the input info is valid, call POST request to update the evaluation in database
							 * then show the new evaluations list if success, otherwise show error message
							 */
							"Apply" : function() {

								var rank = $("#dropdown_rank_id_edit"), date_evaluation = $("#date_evaluation_edit"), detail = $("#details_edit"), bValid = true;

								var evaluate_id = info_evaluation[0], rank_id = rank.val(), evaluated_date = date_evaluation.val(), detail_evaluation = detail.val();

								var allFields = $([]).add(rank).add(date_evaluation).add(detail);

								tips = $(".validateTips_add_evaluation");

								allFields.removeClass("ui-state-error");
								bValid = bValid && checkEmpty(date_evaluation, 'Evaluated date');
								bValid = bValid && checkEmpty(detail, "Detail evaluation");
								bValid = bValid && check500Character(detail, "Detail evaluation");

								if (bValid) {
									$.ajax({
										type : "POST",
										url : CI.BASE_URL + "employee/edit_evaluation",
										async : false,
										data : ( {
											'evaluate_id' : evaluate_id,
											'rank' : rank_id,
											'evaluated_date' : evaluated_date,
											'detail' : detail_evaluation
										}),
										success : function(data) {
											//set data for list evaluations
											$("#content_evaluations").html(data);

										},
										error : function() {
											alert("There are some problem with your operation so it's failed.");
										}
									});
									$(this).dialog("close");

								}

								return false;

							},

							Cancel : function() {
								$(this).dialog("close");
							}
						},
						close : function() {

						}
					});

					$("#dialog-edit-evaluation").dialog("open");

				},

				/*
				 * Ask user delete or not: if yes then delete through POST request, else do nothing
				 */
				"Delete" : function() {
					var evaluate_id = info_evaluation[0];

					if (confirm("Do you really want to delete?")) {
						$.ajax({
							type : "POST",
							async : false,
							url : CI.BASE_URL + "employee/delete_evaluation/" + evaluate_id,
							success : function(data) {
								//set data for list evaluations
								if (data) {
									//$("#content_evaluations").html(data);
									window.location.reload();
								} else {
									alert("There are some problem with your operation so it's failed.");
								}

							},
							error : function() {
								alert("There are some problem with your operation so it's failed.");
							}
						});

						$(this).dialog("close");
					}

				}
			},
			close : function() {

			}
		});
		//end dialog

		$("#dialog-more-evaluation").dialog("open");
		return false;
	});

	//handle for delete project assignment
	$("#delete_project_assignment").live('click', function() {
		var assign_id = $(this).attr('value');
		var r = confirm("Do you really want to delete?");
		if (r == true) {
			$.ajax({
				type : "POST",
				url : CI.BASE_URL + "employee/delete_project_assignment/" + assign_id,
				success : function(data) {
					//set data for list project assignments
					if (!data) {
						alert("There are some problem with your operation so it's failed.");
					} else {
						//$("#content_project_assignments").html(data);
						location.reload();
					}
				},
				error : function() {
					alert("There are some problem with your operation so it's failed.");
				}
			});
		}
		return false;
	});

	//handle for delete profile
	$("#delete_profile").live('click', function() {
		var employee_id = $(this).attr('value');
		var r = confirm("Do you really want to delete?");

		if (r == true) {
			$.ajax({
				type : "POST",
				url : CI.BASE_URL + "employee/delete_profile/" + employee_id,
				success : function(success) {
					console.log(success);
					if (success == 1) {
						//	alert("You deleted the profile sucessfully");
						window.location = CI.BASE_URL + "employee";

					} else {
						alert("There are some problems with your operation.");
					}
				},
				error : function() {
					alert("There are some problem with your operation so it's failed.");
				}
			});
		}
		return false;
	});

	function check500Character(object, name) {
		console.log(object);
		var length = object.val().length;

		if (length > 500) {
			object.addClass("ui-state-error");
			window.alert("The " + name + " field can not exceed 500 characters in length.");
			object.focus();
			return false;
		} else {
			return true;
		}
	};
});
//end file