import $ from 'jquery'
import trans from './../script/functions'

export default class chmSurvey {

  static form (id = null) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('surveys/form'), data: {id: id}}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmSurvey.store'
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static show (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('surveys/' + params.id)})
  }

  static store (event) {
    event.preventDefault()
    var form = $(event.target)[0]
    var data = new window.FormData(form)
    var btn = $(event.target).find('[type="submit"]')
    var btnHtml = btn.html()
    btn.html('<i class="fa fa-circle-o-notch"></i>&nbsp;Traitement en cours...')
    btn.prop('disabled', true)
    var id = $('[name="id"]').val()
    var ajaxParams = {
      id: id,
      type: 'POST',
      url: window.chmSite.url('surveys/store'),
      data: data,
      processData: false,
      contentType: false,
      cache: false,
      timeout: 600000
    }
    if ($(event.target).find('[type="file"]')) ajaxParams.enctype = 'multipart/form-data'
    $.ajax(ajaxParams).done(function (response, textStatus, jqXHR) {
      if (response.status !== 'success') {
        window.chmModal.showAlertMessage(response.status, response.message)
      } else {
        window.chmModal.alert('<i class="fa fa-check-circle"></i>&nbsp;Opération effectuée', response.message, {width: 415, callback: 'location.reload()'})
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      window.chmModal.showAlertMessage('danger', message)
    }).always(function () {
      btn.html(btnHtml)
      btn.prop('disabled', false)
    })
  }

  static delete(event, ids) {
    ids = (typeof event[0] !== 'undefined') ? event : ids
    window.chmModal.show({
      type: 'DELETE',
      url: '/survey/delete',
      data: {
        "ids": ids,
        "_method": 'DELETE',
        "_token": $('input[name="_token"]').val(),
      }
    }, {
      message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + trans("Suppression en cours..."),
      onSuccess: (response) => {
        if ('status' in response && response.status === 'alert') {
          window.chmTable.refresh('#SurveysTableContainer')
        }
      }
    })
  }

}
