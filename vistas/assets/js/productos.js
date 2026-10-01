var filaPre = null;

function v_productos() {
  TablaProductos();
  tablaImpuestosProd();
  tablaClavesProdServ();
  tablaClavesUnidades();

  $("#FormProductos").validate({
    rules: {
      CodigoBarras: {
        required: true,
      },
      Descripcion: {
        required: true,
      },
      PrecioProducto: {
        required: true,
      },
    },
    messages: {
      CodigoBarras: {
        required: "El código de barras del producto es obligatorio",
      },
      Descripcion: {
        required: "La descripción del producto es obligatorio",
      },
      PrecioProducto: {
        required: "El precio del producto es obligatorio",
      },
    },
    submitHandler: function (form) {
      console.log("entro");
      const searchRegExp = new RegExp(",", "g");
      var presentaciones = [];
      if ($("#verPresentaciones").children("tr").length > 0) {
        $("#verPresentaciones")
          .children("tr")
          .each(function (index, el) {
            presentaciones.push({
              ID_Presentacion: $.trim($(this).attr("id")),
              Peso: $.trim($(this).attr("peso")),
              Clave: $.trim($(this).children("td:eq(0)").text()),
              Nombre: $.trim($(this).children("td:eq(1)").text()),
              Abreviatura: $.trim(
                $(this).children("td:eq(2)").text()
              ),
              Costo: $.trim(
                $(this)
                  .children("td:eq(3)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Costo_Bruto: $.trim(
                $(this)
                  .children("td:eq(4)")
                  .children("span:eq(0)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Costo_Neto: $.trim(
                $(this)
                  .children("td:eq(4)")
                  .children("span:eq(1)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Importe: $.trim(
                $(this)
                  .children("td:eq(5)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Codigo: $.trim($(this).children("td:eq(6)").text()),
              Referencia: $.trim(
                $(this).children("td:eq(7)").text()
              ),
              Descuento: $.trim(
                $(this).children("td:eq(8)").text().replace("%", "")
                  .replace(searchRegExp, "")
              )
            });
          });
      }

      var precios = [];
      if ($("#verPreciosProd").children("tr").length > 0) {
        $("#verPreciosProd")
          .children("tr")
          .each(function (index, el) {
            precios.push({
              ID_Precio: $.trim($(this).attr("id")),
              Zona: $.trim(
                $(this).children("td:eq(0)").attr("attrID")
              ),
              Presentacion: $.trim(
                $(this).children("td:eq(1)").text()
              ),
              Nombre: $.trim($(this).children("td:eq(2)").text()),
              Precio: $.trim(
                $(this)
                  .children("td:eq(3)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Precio_Bruto: $.trim(
                $(this)
                  .children("td:eq(4)")
                  .text()
                  .replace("$", "")
                  .replace(searchRegExp, "")
              ),
              Precio_Mayoreo: 0,
            });
          });
      }

      var impuestos = [];
      if ($("#verImpuetsosProd").children("tr").length > 0) {
        $("#verImpuetsosProd")
          .children("tr")
          .each(function (index, el) {
            impuestos.push({
              ID_Impuesto: $.trim($(this).attr("attrID")),
            });
          });
      }

      var proveedores = [];
      if ($("#verProveedoresProd").children("tr").length > 0) {
        $("#verProveedoresProd")
          .children("tr")
          .each(function (index, el) {
            proveedores.push({
              ID_Proveedor: $.trim(
                $(this).children("td:eq(0)").attr("attrID")
              ),
            });
          });
      }

      var stock = [];
      if ($("#verStockProd").children("tr").length > 0) {
        $("#verStockProd")
          .children("tr")
          .each(function (index, el) {
            stock.push({
              ID_Sucursal: $.trim(
                $(this).children("td:eq(0)").attr("attrID")
              ),
              Presentacion: $.trim(
                $(this).children("td:eq(1)").text()
              ),
              Minimo: $.trim(
                $(this)
                  .children("td:eq(2)")
                  .text()
                  .replace(searchRegExp, "")
              ),
              Maximo: $.trim(
                $(this)
                  .children("td:eq(3)")
                  .text()
                  .replace(searchRegExp, "")
              ),
            });
          });
      }

      var bloqueado = "0";
      if ($("#bloquearProducto").prop("checked")) {
        bloqueado = "1";
      }

      var data = new FormData(document.getElementById("FormProductos"));
      data.append("metodo", $("#GuardarProducto").attr("tipo"));
      data.append("accion", "productos");
      data.append("IDProducto", $("#GuardarProducto").attr("attrid"));
      data.append("presentaciones", JSON.stringify(presentaciones));
      data.append("precios", JSON.stringify(precios));
      data.append("impuestos", JSON.stringify(impuestos));
      data.append("proveedores", JSON.stringify(proveedores));
      data.append("stock", JSON.stringify(stock));
      data.append("bloqueado", bloqueado);

      $.ajax({
        url: "index.php",
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $("#carga").show();
        },
      })
        .done(function (res) {
          if ($.trim(res) == "Correcto") {
            if ($("#GuardarProducto").attr("tipo") == "modificar") {
              var tipoAlerta = "modificado";
            } else {
              var tipoAlerta = "guardado";
            }
            Swal.fire({
              icon: "success",
              title: "Producto " + tipoAlerta + " correctamente",
            });

            TablaProductos();
            $("#ModalProductos").modal("hide");
          } else if (
            $.trim(res) ==
            "Error: Duplicate entry '" +
            $("#CodigoBarras").val() +
            "' for key 'Codigo'"
          ) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "El código del producto ya fue registrado, intenta con otro.",
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text:
                "Error inesperado al " +
                $("#GuardarProducto").attr("tipo") +
                " producto.",
            });
            console.log($.trim(res));
          }
        })
        .fail(function () {
          console.log("Error ajax");
        })
        .always(function () {
          $("#carga").hide();
        });
    },
  });

  $("#FormExistenciaProducto").validate({
    rules: {
      SucursalExistencia: {
        required: true,
      },
      CantidadExistencia: {
        required: true,
      },
    },
    messages: {
      SucursalExistencia: {
        required: "La sucursal es obligatoria",
      },
      CantidadExistencia: {
        required: "La cantidad es obligatoria",
      },
    },
    submitHandler: function (form) {
      var data = new FormData(
        document.getElementById("FormExistenciaProducto")
      );
      data.append("metodo", "detalles");
      data.append("accion", "productos");
      data.append("tipo", "AgregarExistenciaProducto");
      data.append(
        "IDProducto",
        $("#GuardarExistenciaProducto").attr("attrid")
      );

      var btn = $("#GuardarExistenciaProducto");
      $.ajax({
        url: "index.php",
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $("#carga").show();
        },
      })
        .done(function (res) {
          if ($.trim(res) == "Correcto") {
            Swal.fire({
              icon: "success",
              title: "Existencia agregada correctamente",
            });
            $("#FormExistenciaProducto").trigger("reset");
            $("#ModalExistenciasProducto").modal("hide");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Error inesperado al agregar existencia.",
            });

            console.log($.trim(res));
          }
        })
        .fail(function () {
          console.log("Error ajax");
        })
        .always(function () {
          $("#carga").hide();
        });
    },
  });

  $("#formModiPresentacion").validate({
    rules: {
      nombrePresentacionM: {
        required: true,
      },
      abreviaturaPresentacionM: {
        required: true,
      },
      costoPresentacionM: {
        required: true,
        min: 0,
      },
      importePresentacionM: {
        required: true,
        min: 0,
      },
      CodigoPresentacionM: {
        required: true,
      }
    },
    messages: {
      nombrePresentacionM: {
        required: "El nombre es requerido.",
      },
      abreviaturaPresentacionM: {
        required: "La abreviatura es requerida.",
      },
      costoPresentacionM: {
        required: "El costo es requerido.",
        min: "El minimo es 0",
      },
      importePresentacionM: {
        required: "El importe es requerido.",
        min: "El minimo es 0",
      },
      CodigoPresentacionM: {
        required: "El codigo de la presentacion es requerido",
      },
    },
    submitHandler: function (form) {
      var filas = $("#verPresentaciones").children(
        'tr[attrID="' + $.trim($("#nombrePresentacion").val()) + '"]'
      );
      var codigoActual = $("#bGuardarPresenta").attr("codigoActual");
      var codigo = $("#CodigoPresentacionM").val();

      var referenciaActual =
        $("#bGuardarPresenta").attr("referenciaActual");
      var referencia = $("#ReferenciaPresentacionM").val();
      if (codigoActual == codigo) {
        //Si no modifica el codigo lo deja igual
        if (referenciaActual == referencia) {
          if (filas.length <= 1) {
            var nombre = $.trim(filaPre.attr("attrID"));
            filaPre.attr(
              "attrID",
              $.trim($("#nombrePresentacionM").val())
            );
            filaPre.attr(
              "Peso",
              $.trim($("#pesoPresentacionM").val())
            );

            filaPre
              .children("td:eq(0)")
              .html($.trim($("#unidadPresentacionM").val()));
            filaPre
              .children("td:eq(1)")
              .html($.trim($("#nombrePresentacionM").val()));
            filaPre
              .children("td:eq(2)")
              .html($.trim($("#abreviaturaPresentacionM").val()));
            filaPre
              .children("td:eq(3)")
              .html(
                '<span class="dinero">' +
                $.trim($("#costoPresentacionM").val()) +
                "</span>"
              );
            filaPre
              .children("td:eq(4)")
              .children('span:eq(1)').html(
                $.trim($("#costoNetoPresentacionM").val())
              );
            filaPre
              .children("td:eq(5)")
              .html(
                '<span class="dinero">' +
                $.trim($("#importePresentacionM").val()) +
                "</span>"
              );
            filaPre
              .children("td:eq(6)")
              .html($.trim($("#CodigoPresentacionM").val()));
            filaPre
              .children("td:eq(7)")
              .html($.trim($("#ReferenciaPresentacionM").val()));
            filaPre
              .children("td:eq(8)")
              .html($.trim($("#descuentoPresentacionM").val()));

            $("#presentacionProdSelect")
              .children('option[value="' + nombre + '"]')
              .attr(
                "value",
                $.trim($("#nombrePresentacionM").val())
              );
            $("#presentacionProdSelect")
              .children('option[value="' + nombre + '"]')
              .html($.trim($("#nombrePresentacionM").val()));
            $("#presentacionProdSelect1")
              .children('option[value="' + nombre + '"]')
              .attr(
                "value",
                $.trim($("#nombrePresentacionM").val())
              );
            $("#presentacionProdSelect1")
              .children('option[value="' + nombre + '"]')
              .html($.trim($("#nombrePresentacionM").val()));
            $("#modalPresentaciones").modal("hide");
            moneda();
          } else {
            Swal.fire({
              icon: "warning",
              title: "Oops...",
              text: "La presentación ya existe, por favor utiliza otra.",
            });
          }
        } else {
          if ($("#ReferenciaPresentacionM").val() != "") {
            var referencia = $("#ReferenciaPresentacionM").val();
            var data =
              "metodo=detalles&accion=productos&tipo=ConsultarValidezReferencia&Referencia=" +
              referencia;
            $.ajax({
              url: "index.php",
              type: "POST",
              data: data,
            })
              .done(function (res) {
                if ($.trim(res) == "Valido") {
                  if (filas.length <= 1) {
                    var nombre = $.trim(
                      filaPre.attr("attrID")
                    );
                    filaPre.attr(
                      "attrID",
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                    filaPre.attr(
                      "Peso",
                      $.trim($("#pesoPresentacionM").val())
                    );

                    filaPre
                      .children("td:eq(0)")
                      .html(
                        $.trim(
                          $(
                            "#unidadPresentacionM"
                          ).val()
                        )
                      );
                    filaPre
                      .children("td:eq(1)")
                      .html(
                        $.trim(
                          $(
                            "#nombrePresentacionM"
                          ).val()
                        )
                      );
                    filaPre
                      .children("td:eq(2)")
                      .html(
                        $.trim(
                          $(
                            "#abreviaturaPresentacionM"
                          ).val()
                        )
                      );
                    filaPre
                      .children("td:eq(3)")
                      .html(
                        '<span class="dinero">' +
                        $.trim(
                          $(
                            "#costoPresentacionM"
                          ).val()
                        ) +
                        "</span>"
                      );
                    filaPre
                      .children("td:eq(4)")
                      .children('span:eq(1)').html(
                        $.trim($("#costoNetoPresentacionM").val())
                      );
                    filaPre
                      .children("td:eq(5)")
                      .html(
                        '<span class="dinero">' +
                        $.trim(
                          $(
                            "#importePresentacionM"
                          ).val()
                        ) +
                        "</span>"
                      );
                    filaPre
                      .children("td:eq(6)")
                      .html(
                        $.trim(
                          $(
                            "#CodigoPresentacionM"
                          ).val()
                        )
                      );
                    filaPre
                      .children("td:eq(7)")
                      .html(
                        $.trim(
                          $(
                            "#ReferenciaPresentacionM"
                          ).val()
                        )
                      );
                    filaPre
                      .children("td:eq(8)")
                      .html(
                        $.trim(
                          $(
                            "#descuentoPresentacionM"
                          ).val()
                        )
                      );

                    $("#presentacionProdSelect")
                      .children(
                        'option[value="' + nombre + '"]'
                      )
                      .attr(
                        "value",
                        $.trim(
                          $(
                            "#nombrePresentacionM"
                          ).val()
                        )
                      );
                    $("#presentacionProdSelect")
                      .children(
                        'option[value="' + nombre + '"]'
                      )
                      .html(
                        $.trim(
                          $(
                            "#nombrePresentacionM"
                          ).val()
                        )
                      );
                    $("#presentacionProdSelect1")
                      .children(
                        'option[value="' + nombre + '"]'
                      )
                      .attr(
                        "value",
                        $.trim(
                          $(
                            "#nombrePresentacionM"
                          ).val()
                        )
                      );
                    $("#presentacionProdSelect1")
                      .children(
                        'option[value="' + nombre + '"]'
                      )
                      .html(
                        $.trim(
                          $(
                            "#nombrePresentacionM"
                          ).val()
                        )
                      );
                    $("#modalPresentaciones").modal("hide");

                    moneda();
                  } else {
                    Swal.fire({
                      icon: "warning",
                      title: "Oops...",
                      text: "La presentación ya existe, por favor utiliza otra.",
                    });
                  }
                } else {
                  Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "Esta referencia ya existe, intenta con otra.",
                  });
                }
              })
              .fail(function () {
                console.log("Error ajax");
              });
          }
        }
      } else {
        var data =
          "metodo=detalles&accion=productos&tipo=ConsultarValidezCodigo&Codigo=" +
          codigo;
        $.ajax({
          url: "index.php",
          type: "POST",
          data: data,
        })
          .done(function (res) {
            if ($.trim(res) == "Valido") {
              if ($("#ReferenciaPresentacionM").val() != "") {
                var referencia = $(
                  "#ReferenciaPresentacionM"
                ).val();
                var data =
                  "metodo=detalles&accion=productos&tipo=ConsultarValidezReferencia&Referencia=" +
                  referencia;
                $.ajax({
                  url: "index.php",
                  type: "POST",
                  data: data,
                })
                  .done(function (res) {
                    if ($.trim(res) == "Valido") {
                      if (filas.length <= 1) {
                        var nombre = $.trim(
                          filaPre.attr("attrID")
                        );
                        filaPre.attr(
                          "attrID",
                          $.trim(
                            $(
                              "#nombrePresentacionM"
                            ).val()
                          )
                        );
                        filaPre.attr(
                          "Peso",
                          $.trim($("#pesoPresentacionM").val())
                        );

                        filaPre
                          .children("td:eq(0)")
                          .html(
                            $.trim(
                              $(
                                "#unidadPresentacionM"
                              ).val()
                            )
                          );
                        filaPre
                          .children("td:eq(1)")
                          .html(
                            $.trim(
                              $(
                                "#nombrePresentacionM"
                              ).val()
                            )
                          );
                        filaPre
                          .children("td:eq(2)")
                          .html(
                            $.trim(
                              $(
                                "#abreviaturaPresentacionM"
                              ).val()
                            )
                          );
                        filaPre
                          .children("td:eq(3)")
                          .html(
                            '<span class="dinero">' +
                            $.trim(
                              $(
                                "#costoPresentacionM"
                              ).val()
                            ) +
                            "</span>"
                          );
                        filaPre
                          .children("td:eq(4)")
                          .children('span:eq(1)').html(
                            $.trim($("#costoNetoPresentacionM").val())
                          );
                        filaPre
                          .children("td:eq(5)")
                          .html(
                            '<span class="dinero">' +
                            $.trim(
                              $(
                                "#importePresentacionM"
                              ).val()
                            ) +
                            "</span>"
                          );
                        filaPre
                          .children("td:eq(6)")
                          .html(
                            $.trim(
                              $(
                                "#CodigoPresentacionM"
                              ).val()
                            )
                          );
                        filaPre
                          .children("td:eq(7)")
                          .html(
                            $.trim(
                              $(
                                "#ReferenciaPresentacionM"
                              ).val()
                            )
                          );

                        $("#presentacionProdSelect")
                          .children(
                            'option[value="' +
                            nombre +
                            '"]'
                          )
                          .attr(
                            "value",
                            $.trim(
                              $(
                                "#nombrePresentacionM"
                              ).val()
                            )
                          );
                        $("#presentacionProdSelect")
                          .children(
                            'option[value="' +
                            nombre +
                            '"]'
                          )
                          .html(
                            $.trim(
                              $(
                                "#nombrePresentacionM"
                              ).val()
                            )
                          );
                        $("#presentacionProdSelect1")
                          .children(
                            'option[value="' +
                            nombre +
                            '"]'
                          )
                          .attr(
                            "value",
                            $.trim(
                              $(
                                "#nombrePresentacionM"
                              ).val()
                            )
                          );
                        $("#presentacionProdSelect1")
                          .children(
                            'option[value="' +
                            nombre +
                            '"]'
                          )
                          .html(
                            $.trim(
                              $(
                                "#nombrePresentacionM"
                              ).val()
                            )
                          );
                        $("#modalPresentaciones").modal(
                          "hide"
                        );

                        moneda();
                      } else {
                        Swal.fire({
                          icon: "warning",
                          title: "Oops...",
                          text: "La presentación ya existe, por favor utiliza otra.",
                        });
                      }
                    } else {
                      Swal.fire({
                        icon: "warning",
                        title: "Oops...",
                        text: "Esta referencia ya existe, intenta con otra.",
                      });
                    }
                  })
                  .fail(function () {
                    console.log("Error ajax");
                  });
              } else {
                if (filas.length <= 1) {
                  var nombre = $.trim(filaPre.attr("attrID"));
                  filaPre.attr(
                    "attrID",
                    $.trim($("#nombrePresentacionM").val())
                  );
                  filaPre.attr(
                    "Peso",
                    $.trim($("#pesoPresentacionM").val())
                  );

                  filaPre
                    .children("td:eq(0)")
                    .html(
                      $.trim(
                        $("#unidadPresentacionM").val()
                      )
                    );
                  filaPre
                    .children("td:eq(1)")
                    .html(
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                  filaPre
                    .children("td:eq(2)")
                    .html(
                      $.trim(
                        $(
                          "#abreviaturaPresentacionM"
                        ).val()
                      )
                    );
                  filaPre
                    .children("td:eq(3)")
                    .html(
                      '<span class="dinero">' +
                      $.trim(
                        $(
                          "#costoPresentacionM"
                        ).val()
                      ) +
                      "</span>"
                    );
                  filaPre
                    .children("td:eq(4)")
                    .children('span:eq(1)').html(
                      $.trim($("#costoNetoPresentacionM").val())
                    );
                  filaPre
                    .children("td:eq(5)")
                    .html(
                      '<span class="dinero">' +
                      $.trim(
                        $(
                          "#importePresentacionM"
                        ).val()
                      ) +
                      "</span>"
                    );
                  filaPre
                    .children("td:eq(6)")
                    .html(
                      $.trim(
                        $("#CodigoPresentacionM").val()
                      )
                    );
                  filaPre
                    .children("td:eq(7)")
                    .html(
                      $.trim(
                        $(
                          "#ReferenciaPresentacionM"
                        ).val()
                      )
                    );

                  $("#presentacionProdSelect")
                    .children(
                      'option[value="' + nombre + '"]'
                    )
                    .attr(
                      "value",
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                  $("#presentacionProdSelect")
                    .children(
                      'option[value="' + nombre + '"]'
                    )
                    .html(
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                  $("#presentacionProdSelect1")
                    .children(
                      'option[value="' + nombre + '"]'
                    )
                    .attr(
                      "value",
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                  $("#presentacionProdSelect1")
                    .children(
                      'option[value="' + nombre + '"]'
                    )
                    .html(
                      $.trim(
                        $("#nombrePresentacionM").val()
                      )
                    );
                  $("#modalPresentaciones").modal("hide");

                  moneda();
                } else {
                  Swal.fire({
                    icon: "warning",
                    title: "Oops...",
                    text: "La presentación ya existe, por favor utiliza otra.",
                  });
                }
              }
            } else {
              Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Este codigo ya existe, intenta con otro.",
              });
            }
          })
          .fail(function () {
            console.log("Error ajax");
          });
      }
    },
  });

  $("#FormExistenciaProductoMod").validate({
    rules: {
      SucursalExistenciaMod: {
        required: true,
      },
      ExistenciaProductoMod: {
        required: true,
      },
    },
    messages: {
      SucursalExistenciaMod: {
        required: "La sucursal es obligatoria",
      },
      ExistenciaProductoMod: {
        required: "La existencia es obligatoria",
      },
    },
    submitHandler: function (form) {
      Swal.fire({
        title: "¿Estás a punto de modificar las existencias de este producto?",
        footer: "<b style='color: red;'>Una vez modificada ya no podrá recuperarse la existencia anterior</b>",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: "No, cancelar",
        confirmButtonText: "Si, continuar",
      }).then((result) => {
        if (result.value) {
          var data = new FormData(
            document.getElementById("FormExistenciaProductoMod")
          );
          data.append("metodo", "detalles");
          data.append("accion", "productos");
          data.append("tipo", "ModificarExistenciaProducto");
          data.append(
            "IDProducto",
            $("#GuardarExistenciaProductoMod").attr("attrid")
          );

          var btn = $("#GuardarExistenciaProductoMod");
          $.ajax({
            url: "index.php",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function () {
              $("#carga").show();
            },
          })
            .done(function (res) {
              if ($.trim(res) == "Correcto") {
                Swal.fire({
                  icon: "success",
                  title: "Existencia modificada correctamente",
                });
                $("#FormExistenciaProductoMod").trigger(
                  "reset"
                );
                $("#ModalModificarExistencias").modal("hide");
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Error inesperado al modificar la existencia.",
                });

                console.log($.trim(res));
              }
            })
            .fail(function () {
              console.log("Error ajax");
            })
            .always(function () {
              $("#carga").hide();
            });
        }
      });
    },
  });

  $(document).on("change", "#SucursalExistenciaMod", function () {
    var sucursal = $(this).val();
    var presentacion = $("#PresentacionesProductoMod").val() || 0;
    var idproducto = $("#GuardarExistenciaProductoMod").attr("attrid");

    var data =
      "metodo=detalles&accion=productos&tipo=ConsultarExistenciaActual&IDProducto=" +
      idproducto +
      "&sucursal=" +
      sucursal +
      "&presentacion=" +
      presentacion;
    $.ajax({
      url: "index.php",
      type: "POST",
      data: data,
    })
      .done(function (res) {
        $("#ExistenciaProductoMod").val(parseFloat($.trim(res)));
      })
      .fail(function () {
        console.log("Error ajax");
      });
  });

  $(document).on("change", "#PresentacionesProductoMod", function () {
    var sucursal = $("#SucursalExistenciaMod").val();
    var presentacion = $(this).val() || 0;
    var idproducto = $("#GuardarExistenciaProductoMod").attr("attrid");

    var data =
      "metodo=detalles&accion=productos&tipo=ConsultarExistenciaActual&IDProducto=" +
      idproducto +
      "&sucursal=" +
      sucursal +
      "&presentacion=" +
      presentacion;
    $.ajax({
      url: "index.php",
      type: "POST",
      data: data,
    })
      .done(function (res) {
        $("#ExistenciaProductoMod").val(res);
      })
      .fail(function () {
        console.log("Error ajax");
      });
  });

  $("#FormAgregarPrecioNuevoProductos").validate({
    rules: {
      ReferenciaPrecioNuevo: {
        required: true,
      },
      Precio3PrecioNuevo: {
        required: true,
      },
      AumentoPrecioNuevo: {
        required: true,
      },
      ZonaPrecioNuevo: {
        required: true,
      },
    },
    messages: {
      ReferenciaPrecioNuevo: {
        required: "La referencia es requerida.",
      },
      Precio3PrecioNuevo: {
        required: "El precio 3 es requerido.",
      },
      AumentoPrecioNuevo: {
        required: "El porcentaje de aumento es requerido.",
      },
      ZonaPrecioNuevo: {
        required: "La zona del precio es requerida.",
      },
    },
    submitHandler: function (form) {
      if ($("#ImpuestosPrecioNuevo").val() == "") {
        Swal.fire({
          icon: "info",
          title: "Nuevo precio agregado correctamente",
        });
      }
      var data = new FormData(
        document.getElementById("FormAgregarPrecioNuevoProductos")
      );
      data.append("metodo", "detalles");
      data.append("accion", "productos");
      data.append("tipo", "InsertarNuevoPrecio3");

      $.ajax({
        url: "index.php",
        type: "POST",
        data: data,
        processData: false,
        contentType: false,
        beforeSend: function () {
          $("#carga").show();
        },
      })
        .done(function (res) {
          console.log(res);
          if ($.trim(res) == "Correcto") {
            Swal.fire({
              icon: "success",
              title: "Precios agregados correctamente",
            });
            $("#FormAgregarPrecioNuevoProductos").trigger("reset");
            $("#ModalAgregarPrecio3").modal("hide");
          } else {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Error inesperado al agregar el nuevo precio.",
            });

            console.log($.trim(res));
          }
        })
        .fail(function () {
          console.log("Error ajax");
        })
        .always(function () {
          $("#carga").hide();
        });
    },
  });
}

function TablaProductos() {
  ajaxMyDatatable({
    table: $("#TablaProductos"),
    colums: ["Codigo", "Descripcion", "Precio", "Detalles", "Acciones"],
    sort: [0, "asc"],
    url: "index.php",
    params: {
      metodo: "consultar",
      accion: "productos",
    },
  });
}

function tablaImpuestosProd() {
  ajaxMyDatatable({
    table: $("#tablaImpuestosProd"),
    colums: ["Nombre", "Porcentaje", "Clave", "Tipo", "Clase", "Acciones"],
    sort: [0, "desc"],
    url: "index.php",
    params: {
      metodo: "detalles",
      accion: "productos",
      tipo: "impuestos",
    },
  });
}

function tablaClavesProdServ() {
  ajaxMyDatatable({
    table: $("#tablaClavesProdServ"),
    colums: ["Clave", "Descripcion", "Palabras", "Acciones"],
    sort: [0, "asc"],
    url: "index.php",
    params: {
      metodo: "detalles",
      accion: "productos",
      tipo: "clavesProdServ",
    },
  });
}

function tablaClavesUnidades() {
  ajaxMyDatatable({
    table: $("#tablaClavesUnidades"),
    colums: ["Clave", "Nombre", "Simbolo", "Acciones"],
    sort: [0, "asc"],
    url: "index.php",
    params: {
      metodo: "detalles",
      accion: "productos",
      tipo: "clavesUnidades",
    },
  });
}

jQuery(document).ready(function ($) {
  $(document).on("click", "#botonNuevoProductos", function () {
    $("#GuardarProducto").attr("tipo", "insertar");
    $("#GuardarProducto").attr("attrid", "");
    document.getElementById("formPreciosProd").reset();
    $("#verPreciosProd").html("");
    $("#FormProductos").trigger("reset");
    $("#TituloModalProductos").text("Agregar nuevo");
    document.getElementById("formPresentaciones").reset();
    $("#verPresentaciones").html("");
    $("#verImpuetsosProd").html("");
    $("#verProveedoresProd").html("");
    $("#verStockProd").html("");
    $("#bloquearProducto").prop("checked", false);
    $("#verImagenProducto").html(
      '<img src="vistas/assets/archivos/fotosProductos/default.jpg" style="width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>'
    );
    JsBarcode("#CodigoB", "CODIGO");
    $("#FilaReferenciaProducto").addClass("oculto");
  });

  $(document).on("click", ".EliminarProducto", function () {
    var boton = $(this);
    var id = $(this).attr("attrid");
    var nombre = $(this).attr("descripcion");
    console.log(id);
    Swal.fire({
      title: "¿Estás a punto de eliminar el producto " + nombre + "?",
      text: "Una vez eliminado ya no podrá ser recuperado",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "No, cancelar",
      confirmButtonText: "Si, eliminar",
    }).then((result) => {
      if (result.value) {
        var data =
          "metodo=eliminar&accion=productos&tipo=EliminarProducto&IDProducto=" +
          id;
        $.ajax({
          url: "index.php",
          type: "POST",
          data: data,
        })
          .done(function (res) {
            console.log(res);
            if ($.trim(res) == "Correcto") {
              TablaProductos();
              Swal.fire({
                icon: "success",
                title: "Producto eliminado correctamente",
              });
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Error inesperado al eliminar el producto.",
              });
            }
          })
          .fail(function () {
            console.log("Error ajax");
          });
      }
    });
  });

  $(document).on("click", ".ModificarProducto", function () {
    $("#FilaReferenciaProducto").removeClass("oculto");
    var id = $(this).attr("attrid");
    $("#verPresentaciones").html("");
    $("#verPreciosProd").html("");
    $("#verImpuetsosProd").html("");
    $("#verProveedoresProd").html("");
    $("#verStockProd").html("");
    $("#GuardarProducto").attr("tipo", "modificar");
    $("#GuardarProducto").attr("attrid", id);
    $("#TituloModalProductos").text("Modificar");
    $("#presentacionProdSelect").html(
      '<option value="">--Seleccione una opción--</option>'
    );
    $("#presentacionProdSelect1").html(
      '<option value="">--Seleccione una opción--</option>'
    );
    $("#bloquearProducto").prop("checked", false);
    $("#precioProductoPres").val("");
    $("#precioProductoPresBruto").val("");
    $("#costoPresentacion").val("");
    $("#costoBrutoPresentacion").val("");

    var data =
      "metodo=detalles&accion=productos&tipo=modificarProducto&IDProducto=" +
      id;

    $.ajax({
      url: "index.php",
      type: "POST",
      data: data,
    })
      .done(function (res) {
        //console.log(res);
        $("#verPresentaciones").html("");
        $("#verPreciosProd").html("");
        $("#verImpuetsosProd").html("");
        $("#verProveedoresProd").html("");
        $("#verStockProd").html("");
        var datos = JSON.parse($.trim(res));

        $("#CodigoBarras").val(datos.Codigo);
        $("#Descripcion").val(datos.Descripcion);
        if (datos.FK_Categoria == "0") {
          datos.FK_Categoria = "";
        }
        $("#Categoria").val(datos.FK_Categoria);
        $("#CostoProducto").val(datos.Costo);
        $("#CostoBrutoProducto").val(datos.Costo_Bruto);
        $("#CostoNetoProducto").val(datos.Costo_Neto);
        $("#PrecioProducto").val(datos.Precio);
        $("#PrecioMayoreo").val(datos.Precio_Mayoreo);
        $("#descuentoProducto").val(datos.Descuento);
        $("#pesoProducto").val(datos.Peso);
        if (datos.FK_Area == "0") {
          datos.FK_Area = "";
        }
        $("#Area").val(datos.FK_Area);
        $("#DetallesProducto").val(datos.Detalles);
        $("#ImporteProducto").val(datos.Importe);
        $("#ReferenciaProducto").val(datos.Referencia);
        $("#claveProdServ").val(datos.Clave_ProdServ_CFDI);
        $("#claveUnidadProd").val(datos.Clave_Unidad_CFDI);
        $("#unidadProd").val(datos.Nombre_Unidad);
        $("#abreUnudadProd").val(datos.Abreviatura_Unidad);
        $("#objImProducto").val(datos.Objeto_Impuesto_CFDI);

        if (datos.Bloqueado == "1") {
          $("#bloquearProducto").prop("checked", true);
        }

        $("#verImagenProducto").html(
          '<img src="vistas/assets/archivos/fotosProductos/' +
          datos.Imagen +
          '" width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;" class="img-thumbnail"><br>'
        );

        $("img").each(function () {
          if ($(this)[0].naturalHeight == 0) {
            $(this).attr(
              "src",
              "vistas/assets/archivos/fotosProductos/default.jpg"
            );
          }
        });

        $("#CodigoBarras").trigger("keyup");

        if (datos.Presentaciones != null) {
          datos.Presentaciones.forEach((presentacion) => {
            var botonEli = "";
            if (parseInt(presentacion.NumProd) == 0) {
              botonEli =
                '<button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button>';
            }

            $("#verPresentaciones").append(
              `<tr codigo="` +
              presentacion.Codigo +
              `" referencia="` +
              presentacion.Referencia +
              `" id="` +
              presentacion.ID_Presentacion +
              `" attrID="` +
              presentacion.Nombre +
              `" peso="` + presentacion.Peso + `"
              >
                        <td>` +
              presentacion.Clave_CFDI +
              `</td>
                        <td>` +
              presentacion.Nombre +
              `</td>
                        <td>` +
              presentacion.Abreviatura +
              `</td>
                        <td><span class="dinero">` +
              presentacion.Costo +
              `</span></td>
                        <td><span class="dinero">` +
              presentacion.Costo_Bruto +
              `</span>
                <br><b>Neto: </b><span class="dinero"> ` + presentacion.Costo_Neto + `</span>
              </td>
                        <td><span class="dinero">` +
              presentacion.Importe +
              `</span></td>
                        <td>` +
              presentacion.Codigo +
              `</td>
                        <td>` +
              presentacion.Referencia +
              `</td>
                        <td><span class="porcentaje">` +
              presentacion.Descuento +
              `</span></td>
                        <td>` +
              botonEli +
              ` <button type="button" class="btn btn-warning btn-sm bModificarPresenta" attrID="` +
              presentacion.ID_Presentacion +
              `"><i class="fas fa-pencil"></i></button>
                                <br><span style="font-size: 12px;"><b>Última fecha de mod. </b> `+ presentacion.Fecha_Costo + `</span>
                        </td>
                            
                    </tr>`);

            $("#presentacionProdSelect").append(
              '<option value="' +
              presentacion.Nombre +
              '">' +
              presentacion.Nombre +
              "</option>"
            );
            $("#presentacionProdSelect1").append(
              '<option value="' +
              presentacion.Nombre +
              '">' +
              presentacion.Nombre +
              "</option>"
            );
          });
        }

        if (datos.Precios != null) {
          datos.Precios.forEach((precio) => {
            $("#verPreciosProd").append(
              `<tr id="` +
              precio.ID_Precio +
              `">
                        <td attrID="` +
              precio.FK_Zona +
              `">` +
              precio.Zona +
              `</td>
                        <td attrID="` +
              precio.FK_Presentacion +
              `">` +
              precio.Presentacion +
              `</td>
                        <td>` +
              precio.Nombre +
              `</td>
                        <td><span class="dinero">` +
              precio.Precio +
              `</span></td>
                        <td><span class="dinero">` +
              precio.Precio_Bruto +
              `</span></td>
                        <td><span class="">` +
              precio.Margen +
              `</span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
                    </tr>`
            );
          });
        }

        if (datos.Impuestos != null) {
          datos.Impuestos.forEach((impuesto) => {
            $("#verImpuetsosProd").append(
              `<tr attrID="` +
              impuesto.ID_Impuesto +
              `">
                        <td>` +
              impuesto.Nombre +
              `</td>
                        <td>` +
              impuesto.Porcentaje +
              `</td>
                        <td>` +
              impuesto.Clave +
              `</td>
                        <td>` +
              impuesto.Tipo +
              `</td>
                        <td>` +
              impuesto.Clase +
              `</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarImpu"><i class="fas fa-trash"></i></button></td>
                    </tr>`
            );
          });
        }

        if (datos.Proveedores != null) {
          datos.Proveedores.forEach((proveedor) => {
            $("#verProveedoresProd").append(
              `<tr id="` +
              proveedor.FK_Proveedor +
              `">
                        <td attrID="` +
              proveedor.FK_Proveedor +
              `">` +
              proveedor.Empresa +
              `/` +
              proveedor.Nombre +
              `</td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarProveedor"><i class="fas fa-trash"></i></button></td>
                    </tr>`
            );
          });
        }

        if (datos.Stocks != null) {
          datos.Stocks.forEach((stock) => {
            $("#verStockProd").append(
              `<tr>
                        <td attrID="` +
              stock.FK_Sucursal +
              `">` +
              stock.Sucursal +
              `</td>
                        <td attrID="` +
              stock.FK_Presentacion +
              `">` +
              stock.Presentacion +
              `</td>
                        <td><span class="cantidad">` +
              stock.Minimo +
              `</span></td>
                        <td><span class="cantidad">` +
              stock.Maximo +
              `</span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarStock"><i class="fas fa-trash"></i></button></td>
                    </tr>`
            );
          });
        }

        moneda();
        $("#ModalProductos").modal("show");
      })
      .fail(function () {
        console.log("Error ajax");
      });
  });

  $(document).on("click", ".AumentarExistencias", function () {
    $("#PresentacionesProducto").html("");
    $("#GuardarExistenciaProducto").attr("attrid", "");
    $("#FormExistenciaProducto").trigger("reset");

    var id = $(this).attr("attrid");
    var data =
      "metodo=detalles&accion=productos&tipo=ConsultarPresentacionesExistencia&IDProducto=" +
      id;
    $.ajax({
      url: "index.php",
      type: "POST",
      data: data,
    })
      .done(function (res) {
        $("#PresentacionesProducto").html(res);
        $("#GuardarExistenciaProducto").attr("attrid", id);
        $("#ModalExistenciasProducto").modal("show");
      })
      .fail(function () {
        console.log("Error ajax");
      });
  });

  $(document).on("click", ".ModificarExistencia", function () {
    var id = $(this).attr("attrid");
    var contra = "";

    Swal.fire({
      title: "Ingresa la contraseña de administrador",
      input: "password",
      inputAttributes: {
        autocapitalize: "off",
      },
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonText: "Continuar",
      showLoaderOnConfirm: true,
      preConfirm: async (login) => {
        try {
          contra = login;
        } catch (error) {
          Swal.showValidationMessage(`
                Request failed: ${error}
              `);
        }
      },
      allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
      if (result.isConfirmed) {
        var data =
          "metodo=detalles&accion=productos&tipo=ConsultarContraAdmin&contrasena=" +
          contra;
        $.ajax({
          url: "index.php",
          type: "POST",
          data: data,
        })
          .done(function (res) {
            console.log(res);
            if ($.trim(res) == "Correcto") {
              $("#ModalModificarExistencias").modal("show");
              $("#PresentacionesProductoMod").html("");
              $("#GuardarExistenciaProductoMod").attr(
                "attrid",
                ""
              );
              $("#FormExistenciaProductoMod").trigger("reset");

              var data =
                "metodo=detalles&accion=productos&tipo=ConsultarPresentacionesExistencia&IDProducto=" +
                id;
              $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
              })
                .done(function (res) {
                  $("#PresentacionesProductoMod").html(res);
                  $("#GuardarExistenciaProductoMod").attr(
                    "attrid",
                    id
                  );
                  $("#ModalExistenciasProductoMod").modal(
                    "show"
                  );
                })
                .fail(function () {
                  console.log("Error ajax");
                });
            } else {
              Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "No tienes permiso de acceder a esta función",
              });
            }
          })
          .fail(function () {
            console.log("Error ajax");
          });
      }
    });
  });

  $(document).on("click", "#verImagenProducto", function () {
    $("#ImagenProducto").trigger("click");
  });

  $(document).on("change", "#ImagenProducto", function () {
    readURL(this, $("#verImagenProducto"));
  });

  $(document).on("click", "#bAgregarImpuestoProd", function () {
    $("#modalImpuestosProducto").modal("show");
  });

  $(document).on("click", ".bSeleccionarIm", function () {
    // Aqui dibuja en la tabla impuestos el row del impuesto seleccionado.
    if (
      $("#verImpuetsosProd").children(
        'tr[attrID="' + $(this).attr("attrID") + '"]'
      ).length == 0
    ) {
      var padre = $(this).parent().parent();
      $("#verImpuetsosProd").append(
        `<tr attrID="` +
        $(this).attr("attrID") +
        `">
                <td>` +
        $.trim(padre.children("td:eq(0)").text()) +
        `</td>
                <td>` +
        $.trim(padre.children("td:eq(1)").text()) +
        `</td>
                <td>` +
        $.trim(padre.children("td:eq(2)").text()) +
        `</td>
                <td>` +
        $.trim(padre.children("td:eq(3)").text()) +
        `</td>
                <td>` +
        $.trim(padre.children("td:eq(4)").text()) +
        `</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarImpu"><i class="fas fa-trash"></i></button></td>
            </tr>`
      );
    }

    // Aqui comienza la edicion o agregacion del precio bruto o costo bruto de cualquier precio o presentacion del producto al añadirse el impuesto.
    let porcenImpuesto = parseFloat(
      $.trim(padre.children("td:eq(1)").text())
    ).toFixed(2);
    let filasPrecios = "";
    let filasPresentacionesProd = "";
    const searchRegExp = new RegExp(",", "g");

    // Traer los precios de la tabla precios del producto dentro del modal de productos.
    let precios = [];
    if ($("#verPreciosProd").children("tr").length > 0) {
      $("#verPreciosProd")
        .children("tr")
        .each(function (index, el) {
          precios.push({
            ID_Precio: $.trim($(this).attr("id")),
            Zona: $.trim(
              $(this).children("td:eq(0)").attr("attrID")
            ),
            NombreZona: $.trim($(this).children("td:eq(0)").text()),
            Presentacion: $.trim(
              $(this).children("td:eq(1)").attr("attrID")
            ),
            NombrePresentacion: $.trim(
              $(this).children("td:eq(1)").text()
            ),
            Nombre: $.trim($(this).children("td:eq(2)").text()),
            Precio: $.trim(
              $(this)
                .children("td:eq(3)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Precio_Bruto: $.trim(
              $(this)
                .children("td:eq(4)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Precio_Mayoreo: 0,
          });
        });
    }
    // console.log({ precios });

    for (const precio of precios) {
      let precioBrutoConImpuesto =
        parseFloat(precio.Precio) /
        (parseFloat(porcenImpuesto) / 100 + 1);
      filasPrecios += `<tr id="${precio.ID_Precio}">
                        <td attrid="${precio.Zona}">${precio.NombreZona}</td>
                        <td attrid="${precio.Presentacion}">${precio.NombrePresentacion
        }</td>
                        <td>${precio.Nombre}</td>
                        <td><span class="dinero">$${precio.Precio}</span></td>
                        <td><span class="dinero">$${precioBrutoConImpuesto.toFixed(
          2
        )}</span></td>
                        <td><span class=""></span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
                    </tr>`;
    }

    $("#verPreciosProd").html(filasPrecios);

    // Traer los costos de la tabla de presentaciones de productos en el modal de productos.
    let costosPresentacionesProductos = [];
    if ($("#verPresentaciones").children("tr").length > 0) {
      $("#verPresentaciones")
        .children("tr")
        .each(function (index, el) {
          costosPresentacionesProductos.push({
            ID_Presentacion: $.trim($(this).attr("id")),
            Clave: $.trim($(this).children("td:eq(0)").text()),
            Nombre: $.trim($(this).children("td:eq(1)").text()),
            Abreviatura: $.trim(
              $(this).children("td:eq(2)").text()
            ),
            Costo: $.trim(
              $(this)
                .children("td:eq(3)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Costo_Bruto: $.trim(
              $(this)
                .children("td:eq(4)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Importe: $.trim(
              $(this)
                .children("td:eq(5)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Codigo: $.trim($(this).children("td:eq(6)").text()),
            Referencia: $.trim($(this).children("td:eq(7)").text()),
          });
        });
    }
    // console.log({ costosPresentacionesProductos });

    for (const costoPresenProd of costosPresentacionesProductos) {
      let costoBrutoPresenProdConImpuesto =
        parseFloat(costoPresenProd.Costo) /
        (parseFloat(porcenImpuesto) / 100 + 1);
      filasPresentacionesProd += `<tr codigo="${costoPresenProd.Codigo
        }" referencia="${costoPresenProd.Referencia}" id="${costoPresenProd.ID_Presentacion
        }" attrid="${costoPresenProd.Nombre}">
                        <td>${costoPresenProd.Clave}</td>
                        <td>${costoPresenProd.Nombre}</td>
                        <td>${costoPresenProd.Abreviatura}</td>
                        <td><span class="dinero">$${costoPresenProd.Costo
        }</span></td>
                        <td><span class="dinero">$${costoBrutoPresenProdConImpuesto.toFixed(
          2
        )}</span></td>
                        <td><span class="dinero">$${costoPresenProd.Importe
        }</span></td>
                        <td>${costoPresenProd.Codigo}</td>
                        <td>${costoPresenProd.Referencia}</td>
                        <td> <button type="button" class="btn btn-warning btn-sm bModificarPresenta" attrid="${costoPresenProd.ID_Presentacion
        }"><i class="fas fa-pencil"></i></button></td>
                    </tr>`;
    }

    $("#verPresentaciones").html(filasPresentacionesProd);

    $("#modalImpuestosProducto").modal("hide");
  });

  // Evento para quitar el impuesto del producto.
  $(document).on("click", ".bQuitarImpu", function () {
    let filasPrecios = "";
    let filasPresentacionesProd = "";
    const searchRegExp = new RegExp(",", "g");

    // Traer los precios de la tabla precios del producto dentro del modal de productos.
    let precios = [];
    if ($("#verPreciosProd").children("tr").length > 0) {
      $("#verPreciosProd")
        .children("tr")
        .each(function (index, el) {
          precios.push({
            ID_Precio: $.trim($(this).attr("id")),
            Zona: $.trim(
              $(this).children("td:eq(0)").attr("attrID")
            ),
            NombreZona: $.trim($(this).children("td:eq(0)").text()),
            Presentacion: $.trim(
              $(this).children("td:eq(1)").attr("attrID")
            ),
            NombrePresentacion: $.trim(
              $(this).children("td:eq(1)").text()
            ),
            Nombre: $.trim($(this).children("td:eq(2)").text()),
            Precio: $.trim(
              $(this)
                .children("td:eq(3)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Precio_Bruto: $.trim(
              $(this)
                .children("td:eq(4)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Precio_Mayoreo: 0,
          });
        });
    }
    console.log({ precios });

    for (const precio of precios) {
      filasPrecios += `<tr id="${precio.ID_Precio}">
                        <td attrid="${precio.Zona}">${precio.NombreZona}</td>
                        <td attrid="${precio.Presentacion}">${precio.NombrePresentacion}</td>
                        <td>${precio.Nombre}</td>
                        <td><span class="dinero">$${precio.Precio}</span></td>
                        <td><span class="dinero">$${precio.Precio}</span></td>
                        <td><span class=""></span></td>
                        <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
                    </tr>`;
    }
    $("#verPreciosProd").html(filasPrecios);

    // Traer los costos de la tabla de presentaciones de productos en el modal de productos.
    let costosPresentacionesProductos = [];
    if ($("#verPresentaciones").children("tr").length > 0) {
      $("#verPresentaciones")
        .children("tr")
        .each(function (index, el) {
          costosPresentacionesProductos.push({
            ID_Presentacion: $.trim($(this).attr("id")),
            Clave: $.trim($(this).children("td:eq(0)").text()),
            Nombre: $.trim($(this).children("td:eq(1)").text()),
            Abreviatura: $.trim(
              $(this).children("td:eq(2)").text()
            ),
            Costo: $.trim(
              $(this)
                .children("td:eq(3)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Costo_Bruto: $.trim(
              $(this)
                .children("td:eq(4)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Importe: $.trim(
              $(this)
                .children("td:eq(5)")
                .text()
                .replace("$", "")
                .replace(searchRegExp, "")
            ),
            Codigo: $.trim($(this).children("td:eq(6)").text()),
            Referencia: $.trim($(this).children("td:eq(7)").text()),
          });
        });
    }
    console.log({ costosPresentacionesProductos });

    for (const costoPresenProd of costosPresentacionesProductos) {
      filasPresentacionesProd += `<tr codigo="${costoPresenProd.Codigo}" referencia="${costoPresenProd.Referencia}" id="${costoPresenProd.ID_Presentacion}" attrid="${costoPresenProd.Nombre}">
                        <td>${costoPresenProd.Clave}</td>
                        <td>${costoPresenProd.Nombre}</td>
                        <td>${costoPresenProd.Abreviatura}</td>
                        <td><span class="dinero">$${costoPresenProd.Costo}</span></td>
                        <td><span class="dinero">$${costoPresenProd.Costo}</span></td>
                        <td><span class="dinero">$${costoPresenProd.Importe}</span></td>
                        <td>${costoPresenProd.Codigo}</td>
                        <td>${costoPresenProd.Referencia}</td>
                        <td> <button type="button" class="btn btn-warning btn-sm bModificarPresenta" attrid="${costoPresenProd.ID_Presentacion}"><i class="fas fa-pencil"></i></button></td>
                    </tr>`;
    }
    $("#verPresentaciones").html(filasPresentacionesProd);

    $(this).parent().parent().remove();
  });

  function readURL(input, ima) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $(ima).html(
          "<img src='" +
          e.target.result +
          "' style='width: 250px; height: 170px; cursor:pointer;border-radius:4px;border:2px solid grey;' class='img-thumbnail'><br>"
        );
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).on("keyup", "#CodigoBarras", function () {
    JsBarcode("#CodigoB", $(this).val());
  });

  $(document).on("click", "#bBuscarClaveProd", function () {
    $("#modalClavesProdServ").modal("show");
  });

  var tipoClaveU = "prod";
  $(document).on("click", "#bBuscarUnidadProd", function () {
    tipoClaveU = "prod";
    $("#modalClavesUnidades").modal("show");
  });

  $(document).on("click", "#bBuscarUnidadPres", function () {
    tipoClaveU = "pres";
    $("#modalClavesUnidades").modal("show");
  });

  $(document).on("click", "#bBuscarUnidadPresM", function () {
    tipoClaveU = "presM";
    $("#modalClavesUnidades").modal("show");
  });

  $(document).on("click", ".bSeleccionarClaveProdServ", function () {
    $("#claveProdServ").val(
      $.trim($(this).parent().parent().children("td:eq(0)").text())
    );

    $("#modalClavesProdServ").modal("hide");
  });

  $(document).on("click", ".bSeleccionarClaveUnidad", function () {
    var padre = $(this).parent().parent();
    if (tipoClaveU == "prod") {
      $("#claveUnidadProd").val(
        $.trim(padre.children("td:eq(0)").text())
      );

      if ($.trim($("#unidadProd").val()) == "") {
        $("#unidadProd").val($.trim(padre.children("td:eq(1)").text()));
      }
      if ($.trim($("#abreUnudadProd").val()) == "") {
        $("#abreUnudadProd").val(
          $.trim(padre.children("td:eq(2)").text())
        );
      }
    } else if (tipoClaveU == "pres") {
      $("#unidadPresentacion").val(
        $.trim(padre.children("td:eq(0)").text())
      );

      if ($.trim($("#nombrePresentacion").val()) == "") {
        $("#nombrePresentacion").val(
          $.trim(padre.children("td:eq(1)").text())
        );
      }
      if ($.trim($("#abreviaturaPresentacion").val()) == "") {
        $("#abreviaturaPresentacion").val(
          $.trim(padre.children("td:eq(2)").text())
        );
      }
    } else {
      $("#unidadPresentacionM").val(
        $.trim(padre.children("td:eq(0)").text())
      );

      if ($.trim($("#nombrePresentacionM").val()) == "") {
        $("#nombrePresentacionM").val(
          $.trim(padre.children("td:eq(1)").text())
        );
      }
      if ($.trim($("#abreviaturaPresentacionM").val()) == "") {
        $("#abreviaturaPresentacionM").val(
          $.trim(padre.children("td:eq(2)").text())
        );
      }
    }

    $("#modalClavesUnidades").modal("hide");
  });

  $(document).on("click", "#bAgergarPresentacion", function () {
    var codigo = $(this)
      .parent()
      .parent()
      .children("td:eq(6)")
      .find("#CodigoPresentacion")
      .val();
    var referencia = $(this)
      .parent()
      .parent()
      .children("td:eq(7)")
      .find("#ReferenciaPresentacion")
      .val();
    console.log(referencia);
    var data =
      "metodo=detalles&accion=productos&tipo=ConsultarValidezCodigo&Codigo=" +
      codigo;
    $.ajax({
      url: "index.php",
      type: "POST",
      data: data,
    })
      .done(function (res) {
        if ($.trim(res) == "Valido") {
          if (
            $("#verPresentaciones").children(
              'tr[codigo="' + codigo + '"]'
            ).length > 0
          ) {
            Swal.fire({
              icon: "warning",
              title: "Oops...",
              text: "Este codigo ya existe, intenta con otro.",
            });
          } else if (
            $("#verPresentaciones").children(
              'tr[referencia="' + referencia + '"]'
            ).length > 0
          ) {
            Swal.fire({
              icon: "warning",
              title: "Oops...",
              text: "Esta referencia ya existe, intenta con otro.",
            });
          } else {
            if (referencia != "") {
              var data =
                "metodo=detalles&accion=productos&tipo=ConsultarValidezReferencia&Referencia=" +
                referencia;
              $.ajax({
                url: "index.php",
                type: "POST",
                data: data,
              })
                .done(function (res) {
                  console.log(res);
                  if ($.trim(res) == "Valido") {
                    $("#bGuardarPres").trigger("click");
                  } else {
                    Swal.fire({
                      icon: "warning",
                      title: "Oops...",
                      text: "Esta referencia ya existe, intenta con otra.",
                    });
                  }
                })
                .fail(function () {
                  console.log("Error ajax");
                });
            } else {
              $("#bGuardarPres").trigger("click");
            }
          }
        } else {
          Swal.fire({
            icon: "warning",
            title: "Oops...",
            text: "Este codigo ya existe, intenta con otro.",
          });
        }
      })
      .fail(function () {
        console.log("Error ajax");
      });
  });

  $(document).on("submit", "#formPresentaciones", function (event) {
    event.preventDefault();
    if (
      $("#verPresentaciones").children(
        'tr[attrID="' + $.trim($("#nombrePresentacion").val()) + '"]'
      ).length == 0
    ) {
      $("#verPresentaciones").append(
        `<tr referencia="` +
        $.trim($("#ReferenciaPresentacion").val()) +
        `" codigo="` +
        $.trim($("#CodigoPresentacion").val()) +
        `" attrID="` +
        $.trim($("#nombrePresentacion").val()) +
        `">
                <td>` +
        $.trim($("#unidadPresentacion").val()) +
        `</td>
                <td>` +
        $.trim($("#nombrePresentacion").val()) +
        `</td>
                <td>` +
        $.trim($("#abreviaturaPresentacion").val()) +
        `</td>
                <td><span class="dinero">` +
        $.trim($("#costoPresentacion").val()) +
        `</span></td>
                <td><span class="dinero">` +
        $.trim($("#costoBrutoPresentacion").val()) +
        `</span></td>
                <td><span class="dinero">` +
        $.trim($("#importePresentacion").val()) +
        `</span></td>
                <td>` +
        $.trim($("#CodigoPresentacion").val()) +
        `</td>
                <td>` +
        $.trim($("#ReferenciaPresentacion").val()) +
        `</td>
                <td><span class="porcentaje">` +
        $.trim($("#descuentoPresentacion").val()) +
        `</span></td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarPresenta"><i class="fas fa-trash"></i></button> <button type="button" class="btn btn-warning btn-sm bModificarPresenta"><i class="fas fa-pencil"></i></button></td>
            </tr>`
      );

      $("#presentacionProdSelect").append(
        '<option value="' +
        $.trim($("#nombrePresentacion").val()) +
        '">' +
        $.trim($("#nombrePresentacion").val()) +
        "</option>"
      );
      $("#presentacionProdSelect1").append(
        '<option value="' +
        $.trim($("#nombrePresentacion").val()) +
        '">' +
        $.trim($("#nombrePresentacion").val()) +
        "</option>"
      );

      document.getElementById("formPresentaciones").reset();
      moneda();
    } else {
      Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: "La presentación ya existe, por favor utiliza otra.",
      });
    }
  });

  //CALCULAR COSTO BRUTO
  $(document).on("keyup", "#costoPresentacion", function () {
    var costobruto = $(this).val();
    if ($("#verImpuetsosProd").children("tr").length > 0) {
      $("#verImpuetsosProd")
        .children("tr")
        .each(function (index, el) {
          var impuesto = $(this)
            .children("td:eq(1)")
            .text()
            .replace("%", "");
          var cantidadImpuesto =
            parseFloat(costobruto) /
            (parseFloat(impuesto) / 100 + 1);
          costobruto = parseFloat(cantidadImpuesto);
        });
      var totalcostobruto = costobruto.toFixed(2);
    } else {
      var totalcostobruto = costobruto;
    }
    $("#costoBrutoPresentacion").val(totalcostobruto);
  });

  /*$(document).on("keyup", "#costoPresentacionM", function () {
    var costobruto = $(this).val();
    if ($("#verImpuetsosProd").children("tr").length > 0) {
      $("#verImpuetsosProd")
        .children("tr")
        .each(function (index, el) {
          var impuesto = $(this)
            .children("td:eq(1)")
            .text()
            .replace("%", "");
          var cantidadImpuesto =
            parseFloat(costobruto) /
            (parseFloat(impuesto) / 100 + 1);
          costobruto = parseFloat(cantidadImpuesto);
        });
      var totalcostobruto = costobruto.toFixed(2);
    } else {
      var totalcostobruto = costobruto;
    }
    $("#costoBrutoPresentacionM").val(totalcostobruto);
  });*/

  $(document).on("click", ".bQuitarPresenta", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", "#bAgergarPrecio", function () {
    $("#bGuardarPrecio").trigger("click");
  });

  $(document).on("submit", "#formPreciosProd", function (event) {
    event.preventDefault();
    $("#verPreciosProd").append(
      `<tr>
            <td attrID="` +
      $.trim($("#zonaPrecioProducto").val()) +
      `">` +
      $.trim($("#zonaPrecioProducto option:selected").text()) +
      `</td>
            <td attrID="` +
      $.trim($("#presentacionProdSelect").val()) +
      `">` +
      $.trim($("#presentacionProdSelect").val()) +
      `</td>
            <td>` +
      $.trim($("#nombrePrecio").val()) +
      `</td>
            <td><span class="dinero">` +
      $.trim($("#precioProductoPres").val()) +
      `</span></td>
            <td><span class="dinero">` +
      $.trim($("#precioProductoPresBruto").val()) +
      `</span></td>
            <td><span class="">0%</td>
            <td><button type="button" class="btn btn-danger btn-sm bQuitarPrecio"><i class="fas fa-trash"></i></button></td>
        </tr>`
    );

    document.getElementById("formPreciosProd").reset();
    moneda();
  });

  $(document).on("keyup", "#precioProductoPres", function () {
    var preciobruto = $(this).val();
    if ($("#verImpuetsosProd").children("tr").length > 0) {
      $("#verImpuetsosProd")
        .children("tr")
        .each(function (index, el) {
          var impuesto = $(this)
            .children("td:eq(1)")
            .text()
            .replace("%", "");
          var cantidadImpuesto =
            parseFloat(preciobruto) /
            (parseFloat(impuesto) / 100 + 1);
          preciobruto = parseFloat(cantidadImpuesto);
        });
      var totalpreciobruto = preciobruto.toFixed(2);
    } else {
      var totalpreciobruto = preciobruto;
    }
    $("#precioProductoPresBruto").val(totalpreciobruto);
  });

  $(document).on("click", ".bQuitarPrecio", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", ".bModificarPresenta", function () {
    filaPre = $(this).parent().parent();
    const searchRegExp = new RegExp(",", "g");
    $("#unidadPresentacionM").val(
      $.trim(filaPre.children("td:eq(0)").text())
    );
    $("#nombrePresentacionM").val(
      $.trim(filaPre.children("td:eq(1)").text())
    );
    $("#abreviaturaPresentacionM").val(
      $.trim(filaPre.children("td:eq(2)").text())
    );
    $("#costoPresentacionM").val(
      $.trim(
        filaPre
          .children("td:eq(3)")
          .text()
          .replace("$", "")
          .replace(searchRegExp, "")
      )
    );
    $("#costoNetoPresentacionM").val(
      $.trim(
        filaPre
          .children("td:eq(4)")
          .children("span:eq(1)")
          .text()
          .replace("$", "")
          .replace(searchRegExp, "")
      )
    );
    $("#importePresentacionM").val(
      $.trim(
        filaPre
          .children("td:eq(5)")
          .text()
          .replace("$", "")
          .replace(searchRegExp, "")
      )
    );
    $("#CodigoPresentacionM").val(
      $.trim(filaPre.children("td:eq(6)").text())
    );
    $("#ReferenciaPresentacionM").val(
      $.trim(filaPre.children("td:eq(7)").text())
    );
    $("#descuentoPresentacionM").val(
      $.trim(
        filaPre
          .children("td:eq(8)")
          .text()
          .replace("%", "")
          .replace(searchRegExp, "")
      )
    );

    $("#pesoPresentacionM").val($.trim(filaPre.attr("peso")) || 0);

    $("#bGuardarPresenta").attr("attrID", $(this).attr("attrID"));
    $("#bGuardarPresenta").attr(
      "codigoActual",
      filaPre.children("td:eq(6)").text()
    );
    $("#bGuardarPresenta").attr(
      "referenciaActual",
      filaPre.children("td:eq(7)").text()
    );
    
    $("#modalPresentaciones").modal("show");
  });

  $(document).on("click", "#bAgergarProveedor", function () {
    $("#bGuardarProveedor").trigger("click");
  });

  $(document).on("submit", "#formProveedoresProd", function (event) {
    event.preventDefault();

    if (
      $("#verProveedoresProd").children(
        'tr[id="' + $.trim($("#proveedorProducto").val()) + '"]'
      ).length == 0
    ) {
      $("#verProveedoresProd").append(
        `<tr id="` +
        $.trim($("#proveedorProducto").val()) +
        `">
                <td attrID="` +
        $.trim($("#proveedorProducto").val()) +
        `">` +
        $.trim($("#proveedorProducto option:selected").text()) +
        `</td>
                <td><button type="button" class="btn btn-danger btn-sm bQuitarProveedor"><i class="fas fa-trash"></i></button></td>
            </tr>`
      );

      document.getElementById("formProveedoresProd").reset();
      moneda();
    } else {
      Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: "El proveedor ya existe, por favor agrega otro.",
      });
    }
  });

  $(document).on("click", ".bQuitarProveedor", function () {
    $(this).parent().parent().remove();
  });

  $(document).on("click", "#bAgergarStock", function () {
    $("#bGuardarStock").trigger("click");
  });

  /*$(document).on('click', '#bAgregarPrecio3', function() {
      $("#bGuardarPrecio3").trigger('click');
  });*/

  $(document).on("submit", "#formStockProd", function (event) {
    event.preventDefault();
    if (
      parseFloat($("#minimoStock").val()) <
      parseFloat($("#maximoStock").val())
    ) {
      var encontro = false;
      if ($("#verStockProd").children("tr").length > 0) {
        for (
          var i = $("#verStockProd").children("tr").length - 1;
          i >= 0;
          i--
        ) {
          if (
            $.trim($("#sucursalProducto").val()) ==
            $.trim(
              $("#verStockProd")
                .children("tr:eq(" + i + ")")
                .children("td:eq(0)")
                .attr("attrID")
            ) &&
            $.trim($("#presentacionProdSelect1").val()) ==
            $.trim(
              $("#verStockProd")
                .children("tr:eq(" + i + ")")
                .children("td:eq(1)")
                .text()
            )
          ) {
            encontro = true;
            break;
          }
        }
      }

      if (encontro == false) {
        $("#verStockProd").append(
          `<tr>
                    <td attrID="` +
          $.trim($("#sucursalProducto").val()) +
          `">` +
          $.trim($("#sucursalProducto option:selected").text()) +
          `</td>
                    <td>` +
          $.trim($("#presentacionProdSelect1").val()) +
          `</td>
                    <td><span class="cantidad">` +
          $("#minimoStock").val() +
          `</span></td>
                    <td><span class="cantidad">` +
          $("#maximoStock").val() +
          `</span></td>
                    <td><button type="button" class="btn btn-danger btn-sm bQuitarStock"><i class="fas fa-trash"></i></button></td>
                </tr>`
        );

        document.getElementById("formStockProd").reset();
        moneda();
      } else {
        Swal.fire({
          icon: "warning",
          title: "Oops...",
          text: "El stock de la presentación en la sucursal ya existe, por favor utiliza otra.",
        });
      }
    } else {
      Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: "El stock mínimo debe ser menor al stock máximo.",
      });
    }
  });

  $(document).on("click", ".bQuitarStock", function () {
    $(this).parent().parent().remove();
  });
});
