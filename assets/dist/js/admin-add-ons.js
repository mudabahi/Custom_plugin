(() => {
  var e;
  (e = jQuery)(document).ready(function () {
    var a = e("#give-licenses-container"),
      n = e("#give-license-activator-wrap"),
      t = e("form", n),
      i = e('input[name="give_license_key"]', n),
      o = e('input[type="submit"]', t),
      d = e(".give-license-notices", n);
    d.on("click", e(".notice-dismiss", d), function (e) {
      d.empty().hide();
    }),
      t.on("submit", function () {
        var n = i.val().trim(),
          t = e('input[name="give_license_activator_nonce"]', e(this))
            .val()
            .trim();
        return (
          d.empty(),
          n
            ? (e
                .ajax({
                  url: ajaxurl,
                  method: "POST",
                  data: {
                    action: "give_get_license_info",
                    license: n,
                    _wpnonce: t,
                  },
                  beforeSend: function () {
                    o.val(o.attr("data-activating")),
                      o.prop("disabled", !0),
                      Give.fn.loader(a);
                  },
                  success: function (e) {
                    if ((d.show(), i.val(""), !0 !== e.success))
                      e.data.hasOwnProperty("errorMsg") && e.data.errorMsg
                        ? d.html(
                            Give.notice.fn.getAdminNoticeHTML(
                              e.data.errorMsg,
                              "error"
                            )
                          )
                        : d.html(
                            Give.notice.fn.getAdminNoticeHTML(
                              give_addon_var.notices.invalid_license,
                              "error"
                            )
                          );
                    else if (
                      e.data.hasOwnProperty("download") &&
                      e.data.download
                    ) {
                      var n =
                        "string" == typeof e.data.download
                          ? give_addon_var.notices.download_file.replace(
                              "{link}",
                              e.data.download
                            )
                          : give_addon_var.notices.download_file.substring(
                              0,
                              give_addon_var.notices.download_file.indexOf(
                                "."
                              ) + 1
                            );
                      d.html(Give.notice.fn.getAdminNoticeHTML(n, "success")),
                        a.parent().parent().removeClass("give-hidden"),
                        a.html(e.data.html);
                    } else
                      d.html(
                        Give.notice.fn.getAdminNoticeHTML(
                          give_addon_var.notices.invalid_license,
                          "error"
                        )
                      );
                  },
                })
                .always(function () {
                  Give.fn.loader(a, { show: !1 }),
                    o.val(o.attr("data-activate")),
                    o.prop("disabled", !1);
                }),
              !1)
            : (d.show(),
              d.html(
                Give.notice.fn.getAdminNoticeHTML(
                  give_addon_var.notices.invalid_license,
                  "error"
                )
              ),
              !1)
        );
      }),
      a.on("click", ".give-button__license-activate", function (n) {
        n.preventDefault();
        var t = e(this),
          i = t.parents(".give-addon-wrap"),
          o = e(".give-license-notice-container", i),
          d = t.prev('.give-license__key input[type="text"]').val().trim();
        o.empty().removeClass("give-addon-notice-shown").show(),
          d
            ? e
                .ajax({
                  url: ajaxurl,
                  method: "POST",
                  data: {
                    action: "give_get_license_info",
                    license: d,
                    single: 1,
                    addon: t.attr("data-addon"),
                    _wpnonce: e("#give_license_activator_nonce").val().trim(),
                  },
                  beforeSend: function () {
                    Give.fn.loader(i);
                  },
                  success: function (e) {
                    !0 !== e.success
                      ? o
                          .addClass("give-addon-notice-shown")
                          .prepend(
                            Give.notice.fn.getAdminNoticeHTML(
                              e.data.errorMsg,
                              "error"
                            )
                          )
                      : e.data.hasOwnProperty("is_all_access_pass") &&
                        e.data.is_all_access_pass
                      ? a.html(e.data.html)
                      : i.replaceWith(e.data.html);
                  },
                })
                .done(function () {
                  Give.fn.loader(i, { show: !1 });
                })
            : o
                .addClass("give-addon-notice-shown")
                .prepend(
                  Give.notice.fn.getAdminNoticeHTML(
                    give_addon_var.notices.invalid_license,
                    "error"
                  )
                ),
          a.on("click", ".notice-dismiss", function () {
            o.slideUp(150, function () {
              o.removeClass("give-addon-notice-shown");
            });
          });
      }),
      a.on("click", ".give-license__deactivate", function (n) {
        n.preventDefault();
        var t = e(this),
          i = t.parents(".give-addon-wrap"),
          o = e(".give-license-notice-container", i),
          d =
            1 <
            t.parents(".give-addon-inner").find(".give-addon-info-wrap").length,
          r = e(".give-addon-wrap").index(i);
        o.empty().removeClass("give-addon-notice-shown").show(),
          e
            .ajax({
              url: ajaxurl,
              method: "POST",
              data: {
                action: "give_deactivate_license",
                license: t.attr("data-license-key"),
                item_name: t.attr("data-item-name"),
                plugin_dirname: t.attr("data-plugin-dirname"),
                _wpnonce: t.attr("data-nonce"),
              },
              beforeSend: function () {
                d ? Give.fn.loader(a) : Give.fn.loader(i);
              },
              success: function (n) {
                !0 === n.success
                  ? (d ? a.html(n.data.html) : i.replaceWith(n.data.html),
                    (i = e(".give-addon-wrap").get(r)),
                    (o = e(".give-license-notice-container", i))
                      .addClass("give-addon-notice-shown")
                      .prepend(
                        Give.notice.fn.getAdminNoticeHTML(n.data.msg, "success")
                      ),
                    a.html().trim().length ||
                      a.parent().parent().addClass("give-hidden"))
                  : o
                      .addClass("give-addon-notice-shown")
                      .prepend(
                        Give.notice.fn.getAdminNoticeHTML(
                          n.data.errorMsg,
                          "error"
                        )
                      );
              },
            })
            .done(function () {
              d
                ? Give.fn.loader(a, { show: !1 })
                : Give.fn.loader(i, { show: !1 });
            }),
          a.on("click", ".notice-dismiss", function () {
            o.slideUp(150, function () {
              o.removeClass("give-addon-notice-shown");
            });
          });
      }),
      a.on("click", ".give-button__license-reactivate", function (n) {
        n.preventDefault();
        var t = e(this),
          i = t.attr("data-license").trim(),
          o = e(".give-addon-wrap").index(d),
          d = t.parents(".give-addon-wrap"),
          r = e(".give-license-notice-container", d);
        r.empty().removeClass("give-addon-notice-shown").show(),
          i
            ? e
                .ajax({
                  url: ajaxurl,
                  method: "POST",
                  data: {
                    action: "give_get_license_info",
                    license: i,
                    single: 1,
                    reactivate: 1,
                    addon: t.attr("data-addon"),
                    _wpnonce: e("#give_license_activator_nonce").val().trim(),
                  },
                  beforeSend: function () {
                    Give.fn.loader(d);
                  },
                  success: function (n) {
                    !0 !== n.success
                      ? (n.data.hasOwnProperty("html") &&
                          n.data.html.length &&
                          (n.data.hasOwnProperty("is_all_access_pass") &&
                          n.data.is_all_access_pass
                            ? a.html(n.data.html)
                            : d.replaceWith(n.data.html)),
                        (d = e(".give-addon-wrap").get(o)),
                        (r = e(".give-license-notice-container", d))
                          .addClass("give-addon-notice-shown")
                          .prepend(
                            Give.notice.fn.getAdminNoticeHTML(
                              n.data.errorMsg,
                              "error"
                            )
                          ))
                      : n.data.hasOwnProperty("is_all_access_pass") &&
                        n.data.is_all_access_pass
                      ? a.html(n.data.html)
                      : d.replaceWith(n.data.html);
                  },
                })
                .done(function () {
                  Give.fn.loader(d, { show: !1 });
                })
            : r
                .addClass("give-addon-notice-shown")
                .prepend(
                  Give.notice.fn.getAdminNoticeHTML(
                    give_addon_var.notices.invalid_license,
                    "error"
                  )
                ),
          a.on("click", ".notice-dismiss", function () {
            r.slideUp(150, function () {
              r.removeClass("give-addon-notice-shown");
            });
          });
      }),
      e("#give-button__refresh-licenses").on("click", function (n) {
        n.preventDefault();
        var t = e(this);
        e.ajax({
          url: ajaxurl,
          method: "POST",
          data: {
            action: "give_refresh_all_licenses",
            _wpnonce: t.attr("data-nonce"),
          },
          beforeSend: function () {
            t.text(t.attr("data-activating")), Give.fn.loader(a);
          },
          success: function (n) {
            !0 === n.success &&
              (a.html(n.data.html),
              e("#give-last-refresh-notice").text(n.data.lastUpdateMsg)),
              (n.success && !n.data.refreshButton) || t.prop("disabled", !0);
          },
        }).done(function () {
          Give.fn.loader(a, { show: !1 }), t.text(t.attr("data-activate"));
        });
      });
  }),
    e(document).ready(function () {
      var a = e(".give-upload-addon-form-wrap"),
        n = e("form", a),
        t = e('input[type="file"]', n),
        i = e(".give-activate-addon-wrap", n),
        o = e("button", n),
        d = e(".give-addon-upload-notices", n),
        r = e("#give-licenses-container");
      function s(t) {
        d.empty(),
          e
            .ajax({
              url: ""
                .concat(ajaxurl, "?action=give_upload_addon&_wpnonce=")
                .concat(e('input[name="_give_upload_addon"]', n).val().trim()),
              method: "POST",
              data: t,
              contentType: !1,
              processData: !1,
              dataType: "json",
              beforeSend: function () {
                d.show(),
                  Give.fn.loader(a, {
                    loadingText:
                      Give.fn.getGlobalVar("loader_translation").uploading,
                  });
              },
              success: function (e) {
                var a;
                if (!0 === e.success)
                  return (
                    d.hide(),
                    i.show(),
                    o.attr("data-pluginPath", e.data.pluginPath),
                    o.attr("data-pluginName", e.data.pluginName),
                    o.attr("data-nonce", e.data.nonce),
                    void r.html(e.data.licenseSectionHtml)
                  );
                (a =
                  e.data.hasOwnProperty("errorMsg") && e.data.errorMsg
                    ? e.data.errorMsg
                    : e.data.error),
                  d.html(Give.notice.fn.getAdminNoticeHTML(a, "error"));
              },
            })
            .always(function () {
              Give.fn.loader(a, { show: !1 });
            });
      }
      a.on("drop", function (a) {
        a.stopPropagation(),
          a.preventDefault(),
          e(this).removeClass("give-dropzone-active");
        var n = a.originalEvent.dataTransfer.files,
          t = new FormData();
        t.append("file", n[0]), s(t);
      }),
        n
          .on("dragover", function (a) {
            e(this).addClass("give-dropzone-active");
          })
          .on("dragleave", function (a) {
            e(this).removeClass("give-dropzone-active");
          }),
        d.on("click", e(".notice-dismiss", d), function (e) {
          d.empty().hide(), n.removeClass("give-dropzone-active");
        }),
        t.on("change", function (e) {
          e.stopPropagation(), e.preventDefault();
          var a = new FormData(),
            n = t[0].files[0];
          if (!n) return !1;
          a.append("file", n), s(a);
        }),
        o.on("click", function (a) {
          a.preventDefault(),
            d.empty(),
            e
              .ajax({
                url: ajaxurl,
                method: "POST",
                data: {
                  action: "give_activate_addon",
                  plugin: o.attr("data-pluginPath"),
                  _wpnonce: o.attr("data-nonce"),
                },
                beforeSend: function () {
                  d.show(), o.text(o.attr("data-activating"));
                },
                success: function (e) {
                  if (!0 === e.success) {
                    var a = give_addon_var.notices.addon_activated.replace(
                      "{pluginName}",
                      o.attr("data-pluginName")
                    );
                    return (
                      d.show(),
                      d.html(Give.notice.fn.getAdminNoticeHTML(a, "success")),
                      void r.html(e.data.licenseSectionHtml)
                    );
                  }
                  e.data.hasOwnProperty("errorMsg") && e.data.errorMsg
                    ? d.html(
                        Give.notice.fn.getAdminNoticeHTML(
                          e.data.errorMsg,
                          "error"
                        )
                      )
                    : Give.notice.fn.getAdminNoticeHTML(
                        give_addon_var.notices.addon_activation_error,
                        "error"
                      );
                },
              })
              .always(function () {
                o.text(o.attr("data-activate")), i.hide();
              });
        });
    });
})();
