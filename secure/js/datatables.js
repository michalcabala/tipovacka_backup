// Call the dataTables jQuery plugin
$(document).ready(function() {
  var table = $('#dataTable').DataTable( {
    stateSave: true,
    stateSaveCallback: function(settings,data) {
      localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
    },
    stateLoadCallback: function(settings) {
      return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
    },
    "columnDefs": [{ targets: 'no-sort', orderable: false }],
    "lengthMenu": [ [25, 50, 100, 500, -1], [25, 50, 100, 500, "Vše"] ],
    lengthChange: false,
    buttons: ['pageLength', 'copy', 'excel', 'pdf', 'colvis'],
    "pageLength": 100,
    "dom": "<'row'<'col-6 col-md-6 pr-0'l><'col-6 col-md-6 pl-0'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    language: {
      "emptyTable": "Tabulka neobsahuje žádná data",
      "info": "Zobrazuji _START_ až _END_ z celkem _TOTAL_ záznamů",
      "infoEmpty": "Zobrazuji 0 až 0 z 0 záznamů",
      "infoFiltered": "(filtrováno z celkem _MAX_ záznamů)",
      "infoThousands": " ",
      "lengthMenu": "Zobraz _MENU_",
      "loadingRecords": "Načítám...",
      "processing": "Provádím...",
      "search": "Hledat:",
      "zeroRecords": "Žádné záznamy nebyly nalezeny",
      "paginate": {
        "first": "První",
        "last": "Poslední",
        "next": "Další",
        "previous": "Předchozí"
      },
      "aria": {
        "sortAscending": ": aktivujte pro řazení sloupce vzestupně",
        "sortDescending": ": aktivujte pro řazení sloupce sestupně"
      },
      "buttons": {
        "colvis": "Zobrazení sloupců",
        "colvisRestore": "Původní nastavení",
        "collection": "Kolekce <span class=\"ui-button-icon-primary ui-icon ui-icon-triangle-1-s\"><\/span>",
        "copy": "Kopírovat",
        "copyKeys": "Stlačte ctrl nebo u2318 + C pro kopírování dat tabulky do systémové schránky. Pro zrušení klepněte na tuhle správne nebo stiskněte ESC.",
        "copySuccess": {
          "1": "Skopírován 1 řádek do schránky",
          "_": "SKopírováno %d řádků do schránky"
        },
        "copyTitle": "Kopírovat do schránky",
        "csv": "CSV",
        "excel": "Excel",
        "pageLength": {
          "-1": "Zobrazit všechny řádky",
          "1": "Zobrazit 1 řádek",
          "_": "Zobrazit %d řádků"
        },
        "pdf": "PDF",
        "print": "Tisknout"
      },
      "searchBuilder": {
        "add": "Přidat podmínku",
        "clearAll": "Smazat vše",
        "condition": "Podmínka",
        "conditions": {
          "date": {
            "after": "po",
            "before": "před",
            "between": "mezi",
            "empty": "prázdné",
            "equals": "rovno",
            "not": "není",
            "notBetween": "není mezi",
            "notEmpty": "není prázdné"
          },
          "number": {
            "between": "mezi",
            "empty": "prázdné",
            "equals": "rovno",
            "gt": "větší",
            "gte": "rovno a větší",
            "lt": "menší",
            "lte": "rovno a menší",
            "not": "není",
            "notBetween": "není mezi",
            "notEmpty": "není prázdné"
          },
          "string": {
            "contains": "obsahuje",
            "empty": "prázdné",
            "endsWith": "končí na",
            "equals": "rovno",
            "not": "není",
            "notEmpty": "není prázdné",
            "startsWith": "začíná na"
          },
          "array": {
            "equals": "rovno",
            "empty": "prázdné",
            "contains": "obsahuje",
            "not": "není",
            "notEmpty": "není prázdné",
            "without": "neobsahuje"
          }
        },
        "data": "Sloupec",
        "logicAnd": "A",
        "logicOr": "NEBO",
        "title": {
          "0": "Rozšířený filtr",
          "_": "Rozšířený filtr (%d)"
        },
        "value": "Hodnota",
        "button": {
          "0": "Rozšířený filtr",
          "_": "Rozšířený filtr (%d)"
        },
        "deleteTitle": "Smazat filtrovací pravidlo"
      },
      "select": {
        "1": "Vybrán %d záznam",
        "2": "Vybrány %d záznamy",
        "_": "Vybráno %d záznamů",
        "cells": {
          "1": "Vybrán 1 záznam",
          "_": "Vybráno %d záznamů"
        },
        "columns": {
          "1": "Vybrán 1 sloupec",
          "_": "Vybráno %d sloupců"
        }
      },
      "autoFill": {
        "cancel": "Zrušit",
        "fill": "Vyplnit všechny buňky s <i>%d<i><\/i><\/i>",
        "fillHorizontal": "Vyplnit buňky horizontálne",
        "fillVertical": "Vyplnit buňky vertikálne"
      },
      "searchPanes": {
        "clearMessage": "Smazat vše",
        "collapse": {
          "0": "Vyhledávací Panely",
          "_": "Vyhledávací Panely (%d)"
        },
        "count": "{total}",
        "countFiltered": "{shown} ({total})",
        "emptyPanes": "Žádné Vyhledávací Panely",
        "loadMessage": "Načítám Vyhledávací Panely",
        "title": "Aktivních filtrů - %d"
      },
      "thousands": " "
    }
  } );

  table.buttons().container()
      .appendTo( '#dataTable_wrapper .col-md-6:eq(0)' );
} );