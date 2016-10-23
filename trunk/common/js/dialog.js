jQuery(document).ready(function() {
  // fix bug input search
  $('#keyword').click(function() {
    var val = $(this).attr('value');
    if (val) {
      // set empty
      $(this).attr('value', '');
      // add input flag: change new search_string = 1
      $(this).parent().append('<input type="hidden" name="new_str" id="new_str" value="1"/>');
    }
  });
});

jQuery(document).ready(function() {
  // fix bug input search
  $('#keyword_employee').click(function() {
    var val = $(this).attr('value');
    if (val) {
      // set empty
      $(this).attr('value', '');
      // add input flag: change new search_string = 1
      $(this).parent().append('<input type="hidden" name="new_str_employee" id="new_str_employee" value="1"/>');
    }
  });
});


$(function() {
  $("#start_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });
  $("#end_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#project_start_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#project_end_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#employee_start_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#employee_end_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#assign_start_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

  $("#assign_end_date").datepicker({
    dateFormat : 'dd-mm-yy'
  });

});

function updateTips(t) {
  tips.text(t).addClass("ui-state-highlight");
  setTimeout(function() {
    tips.removeClass("ui-state-highlight", 1500);
  }, 500);
}

function checkLength(o, n, min, max) {
  if (o.val().length > max || o.val().length < min) {
    o.addClass("ui-state-error");
    window.alert("Length of " + n + " must be between " + min + " and " + max + ".");
    return false;
  } else {
    return true;
  }
}

function checkTwoDate(startDate, endDate) {
  startDate = startDate.val();
  startDate = startDate.split("-");
  newStartDate = startDate[1] + "/" + startDate[0] + "/" + startDate[2];
  startDate = new Date(newStartDate).getTime();

  endDateVal = endDate.val();
  endDateVal = endDateVal.split("-");
  newEndDate = endDateVal[1] + "/" + endDateVal[0] + "/" + endDateVal[2];
  endDateVal = new Date(newEndDate).getTime();

  if (startDate > endDateVal) {
    window.alert('Please input end date greater than start date.');
    endDate.focus();
    return false;
  } else {
    return true;
  }
}

function checkTwoDate2(startDate, endDate) {
  startDateVal = startDate.val();
  startDateVal = startDateVal.split("-");
  newStartDate = startDateVal[1] + "/" + startDateVal[0] + "/" + startDateVal[2];
  startDateVal = new Date(newStartDate).getTime();
  endDate = endDate.html();
  endDate = endDate.split("-");
  newEndDate = endDate[1] + "/" + endDate[0] + "/" + endDate[2];
  endDate = new Date(newEndDate).getTime();
  if (startDateVal < endDate) {
    window.alert('Please input employee start date greater than or equal project start date.');
    startDate.focus();
    return false;
  } else {
    return true;
  }
}

function checkTwoDate3(startDate, endDate) {
  startDateVal = startDate.val();
  startDateVal = startDateVal.split("-");
  newStartDate = startDateVal[1] + "/" + startDateVal[0] + "/" + startDateVal[2];
  startDateVal = new Date(newStartDate).getTime();
  endDate = endDate.html();
  endDate = endDate.split("-");
  newEndDate = endDate[1] + "/" + endDate[0] + "/" + endDate[2];
  endDate = new Date(newEndDate).getTime();
  if (startDateVal > endDate) {
    window.alert('Please input employee end date less than or equal project end date.');
    startDate.focus();
    return false;
  } else {
    return true;
  }
}


function checkEmpty(o, n) {
  if ($.trim(o.val()) === "") {
    o.addClass("ui-state-error");
    window.alert("Please input " + n + ".");
    o.focus();
    return false;
  } else {
    return true;
  }
}

function updateTips_Evaluations(t) {
  // var tips = $(".validateTips_add_evaluation");
  tips.text(t).addClass("ui-state-highlight");
  setTimeout(function() {
    tips.removeClass("ui-state-highlight", 1500);
  }, 500);

}

function checkDate(o, str) {
  if (o.val() == "") {
    o.addClass("ui-state-error");
    window.alert("Please fill in " + str + " date.");
    o.focus();
    return false;
  } else {
    return true;
  }
}

function checkRegexp(o, regexp, n) {
  if (!(regexp.test(o.val()))) {
    o.addClass("ui-state-error");
    updateTips(n);
    return false;
  } else {
    return true;
  }
}

function addItem() {
  var name = $("#name"), start_date = $("#start_date"), end_date = $("#end_date"), allFields = $([]).add(name).add(start_date).add(end_date), tips = $(".validateTips");

  $("#dialog-form").dialog({
    autoOpen : false,
    height : 350,
    width : 350,
    modal : true,
    buttons : {
      "Add New Project" : function() {
        var bValid = true;
        allFields.removeClass("ui-state-error");
        bValid = bValid && checkEmpty(name, "Project Name");
        bValid = bValid && checkLength(name, "project name", 2, 254);
        bValid = bValid && checkDate(start_date, 'start');
        bValid = bValid && checkDate(end_date, 'end');
        bValid = bValid && checkTwoDate(start_date, end_date);

        if (bValid) {
          $('#appForm').submit();
          $(this).dialog("close");
        }
      },
      Cancel : function() {
        $(this).dialog("close");
      }
    },
    close : function() {
      allFields.val("").removeClass("ui-state-error");
    }
  });
  $("#dialog-form").dialog("open");
}
function editItem(id) {
  $("#project_name").val($("#name_" + id).html());
  $("#project_start_date").val($("#start_" + id).html());
  $("#project_end_date").val($("#end_" + id).html());
  $("#project_id").val(id);
  var name = $("#project_name"), start_date = $("#project_start_date"), end_date = $("#project_end_date"), allFields = $([]).add(name).add(start_date).add(end_date), tips = $(".validateTips");

  $("#dialog-form-edit").dialog({
    autoOpen : false,
    height : 350,
    width : 350,
    modal : true,
    buttons : {
      "Edit Project" : function() {
        var bValid = true;
        allFields.removeClass("ui-state-error");
        bValid = bValid && checkEmpty(name, "Project Name");
        bValid = bValid && checkLength(name, "project name", 2, 254);
        bValid = bValid && checkDate(start_date, 'start');
        bValid = bValid && checkDate(end_date, 'end');
        bValid = bValid && checkTwoDate(start_date, end_date);

        if (bValid) {
          $('#appFormEdit').submit();
          $(this).dialog("close");
        }
      },
      Cancel : function() {
        $(this).dialog("close");
      }
    },
    close : function() {
      allFields.val("").removeClass("ui-state-error");
    }
  });
  $("#dialog-form-edit").dialog("open");
}

function assignEmployee() {
  var name = $("#assign_name"), start_date = $("#assign_start_date"), end_date = $("#assign_end_date"), allFields = $([]).add(name).add(start_date).add(end_date), tips = $(".validateTips");
  projectDetailStartDate = $("#project_detail_start_date");
  //projectDetailStartDate = projectDetailStartDate.split("-");
  //projectDetailStartDate = projectDetailStartDate[1] + "/" + projectDetailStartDate[0] + "/" + projectDetailStartDate[2];
  //projectDetailStartDate = new Date(projectDetailStartDate).getTime();
  
  projectDetailEndDate = $("#project_detail_end_date");
  //projectDetailEndDate = projectDetailEndDate.split("-");
  //projectDetailEndDate = projectDetailEndDate[1] + "/" + projectDetailEndDate[0] + "/" + projectDetailEndDate[2];
  //projectDetailEndDate = new Date(projectDetailEndDate).getTime();
  
  //start_date = start_date.val();
  //alert(start_date);
  $("#assign-dialog-form").dialog({
    autoOpen : false,
    height : 350,
    width : 350,
    modal : true,
    buttons : {
      "Assign" : function() {
        var bValid = true;
        allFields.removeClass("ui-state-error");
        bValid = bValid && checkLength(name, "project name", 2, 254);
        bValid = bValid && checkDate(start_date, 'start');
        bValid = bValid && checkTwoDate2(start_date, projectDetailStartDate);
        bValid = bValid && checkDate(end_date, 'end');
        bValid = bValid && checkTwoDate3(end_date, projectDetailEndDate);
        bValid = bValid && checkTwoDate(start_date, end_date);
       
        //alert(start_date);
        if (bValid) {
          $('#assignForm').submit();
          $(this).dialog("close");
        }
      },
      Cancel : function() {
        $(this).dialog("close");
      }
    },
    close : function() {
      start_date.val("").removeClass("ui-state-error");
      end_date.val("").removeClass("ui-state-error");
    }
  });
  $("#assign-dialog-form").dialog("open");
}
jQuery(document).ready(function($) {
  $('#assignEmployee').click(function() {

    projectDetailStartDate = $("#project_detail_start_date").val();
    projectDetailStartDate = projectDetailStartDate.split("-");
    projectDetailStartDate = projectDetailStartDate[1] + "/" + projectDetailStartDate[2] + "/" + projectDetailStartDate[0];
    projectDetailStartDate = new Date(projectDetailStartDate).getTime();

    projectDetailEndDate = $("#project_detail_end_date").val();
    projectDetailEndDate = projectDetailEndDate.split("-");
    projectDetailEndDate = projectDetailEndDate[1] + "/" + projectDetailEndDate[2] + "/" + projectDetailEndDate[0];
    projectDetailEndDate = new Date(projectDetailEndDate).getTime();
    
    
    employeeStartDate = $("#employee_start_date").val();

    if (employeeStartDate == "") {
      window.alert('Please input start date.');
      $("#employee_start_date").focus();
      return false;
    }
    
    var matches = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(employeeStartDate);
    if (matches == null) {
      window.alert('Please input start date correct date format(dd-mm-yyyy)');
      $("#employee_start_date").focus();
      return false;
    }
    
    
    employeeStartDate = employeeStartDate.split("-");
    employeeStartDate = employeeStartDate[1] + "/" + employeeStartDate[0] + "/" + employeeStartDate[2];
    employeeStartDate = new Date(employeeStartDate).getTime();

    if (employeeStartDate < projectDetailStartDate) {
      window.alert('Please input employee start date greater than project start date.');
      $("#employee_start_date").focus();
      return false;
    }
    
    
    employeeEndDate = $("#employee_end_date").val();
    
    if (employeeEndDate == '') {
      window.alert('Please input end date.');
      $("#employee_end_date").focus();
      return false;
    }
    var matches = /^(\d{2})[-\/](\d{2})[-\/](\d{4})$/.exec(employeeEndDate);
    if (matches == null) {
      window.alert('Please input end date correct date format(dd-mm-yyyy)');
      return false;
    }
    
    employeeEndDate = employeeEndDate.split("-");
    employeeEndDate = employeeEndDate[1] + "/" + employeeEndDate[0] + "/" + employeeEndDate[2];
    employeeEndDate = new Date(employeeEndDate).getTime();

    if (employeeEndDate > projectDetailEndDate) {
      window.alert('Please input employee end date less than project end date.');
      $("#employee_end_date").focus();
      return false;
    }

    if (employeeStartDate > employeeEndDate) {
      window.alert('Please input end date greater than start date.');
      $("#employee_end_date").focus();
      return false;
    }

    if (($('.employeeId:checked').length) == 0) {
      window.alert('Please select employee.');
      return false;
    }
    document.assignmentForm.submit();
    return true;
  });
});
