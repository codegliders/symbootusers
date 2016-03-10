jQuery("#tblDgUsers").jqGrid({
    url: 'server.php?q=2', 
    datatype: "json", 
    colNames: ['Id', 'Username', 'Email', 'Active'], 
    colModel: [{name: 'id', index: 'id', width: 55}, 
        {name: 'username', index: 'username', width: 90}, 
        {name: 'email', index: 'email', width: 100}, 
        {name: 'isActive', index: 'isActive', width: 80, align: "right"}, 
    ], 
    rowNum: 10, 
    rowList: [10, 20, 30], 
    pager: '#pager2', sortname: 
            'id', 
    viewrecords: true, 
    sortorder: "desc", 
    caption: "Utenti"});
jQuery("#list2").jqGrid('navGrid', '#pgrGridUsers', {edit: false, add: false, del: false});
