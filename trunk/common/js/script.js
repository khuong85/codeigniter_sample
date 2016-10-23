/**
 * Some script for delete employee, add assign, remove assign
 *
 * @author: phamhoaian
 */

jQuery(document).ready(function() {

	// check delete for delete employee.
	$(".bt_delete").click(function() {
		if (confirm('Are you sure to delete?'))
			return true;
		else
			return false;
	});

	// change notfound image.
	$('a.img > img').error(function() {
		$(this).unbind("error").attr("src", CI.BASE_URL + "/common/uploads/noimage2.png");
	});

	/**
	 * Check any data of project assignment panel
	 */
	function check_pa_data() {
		// check many checked project
		if ($('.check_project:checked').length == 0) {
			alert('Please select someone project!');
			return false;
		} else if (false) {

		}
		return true;
	};

	/**
	 * paging for Project assign
	 */
	function paging_assign_content(event) {
		event.preventDefault();
		var str = $('#form_assign div.input input.search_field').val();
		str = (str !== null && str !== '' && ( typeof str) != 'undefined') ? str : '';
		var ajax_url = CI.BASE_URL + 'employee/search/' + employee_id;

		// for pagination
		var link = event.currentTarget.href;
		if ( typeof link != 'undefined') {
			var offset = link.split('/').pop();
			ajax_url += '/' + offset;
		}

		$.post(ajax_url, {
			search_str : str
		}, function(data) {
			$("#project_list").html(data);
			$('.paging_links > a').click(function(event) {
				paging_assign_content(event);
			});
		}).fail(function() {
			alert("There are some problem with your operation so it's failed.");
		});
	};

	// cacth event link for pagination.
	$('.paging_links > a').click(function(event) {
		paging_assign_content(event);
	});

	// catch event press Enter in search box of popup Project assignment.
	$('#form_assign div.input input.search_field').keyup(function(event) {
		paging_assign_content(event);
	}).keypress(function(e) {
		var keyCode = e.KeyCode ? e.KeyCode : e.which;
		if (keyCode == 13)
			e.preventDefault();
	});
	;

	$('#form_assin div.button #button_search').click(function(event) {
		alert('wlfkjasdlkj');
		paging_assign_content(event);
	});

	// change missing for submit this form when press enter.
	$('#assign-date .search_form input.search').keypress(function(e) {
		var keyCode = e.KeyCode ? e.KeyCode : e.which;
		if (keyCode == 13)
			e.preventDefault();
	});

	$(".datetime").datepicker({
		dateFormat : 'dd-mm-yy',
		changeMonth : true,
		changeYear : true,
		yearRange : '1970:2020',
		option : {
			disabed : true
		}
	}).attr('readonly', 'readonly');

	//fix bug input search in 'Employee manage' layout
	$('#search_string').click(function() {
		var val = $(this).attr('value');
		if (val) {
			//set empty
			$(this).attr('value', '');
			//add input flag: change new search_string = 1
			$(this).parent().append('<input type="hidden" name="new_str" id="new_str" value="1"/>');
		}
	});

	//disable submit when click cancel button( update profile, project assignment).
	$('form :input.cancel_button').click(function(e) {
		return false;
	});
});

/**
 * get current url, and del last string
 */
function get_url() {
	var url = document.URL;
	var temp = url.split('/');
	temp.pop();
	url = temp.join('/');
	return url;
}