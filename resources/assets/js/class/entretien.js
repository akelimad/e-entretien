import $ from 'jquery'

export default class chmEntretien {

  static entretiens () {
    var ids = {}
    $('.usersId:checked').each(function (index) {
      ids[index] = $(this).val()
    })
    console.log(ids)
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/list'), data: {ids: ids}}, {
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmEntretien.storeCheckedUsers'
      },
      footer: {
        label: 'Sauvegarder'
      }
    })
  }

  static form (id = null) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/form'), data: {id: id}}, {
      width: 800,
      form: {
        class: 'allInputsFormValidation form-horizontal',
        callback: 'chmEntretien.store'
      }
    })
  }

  static show (id) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + id + '/show')})
  }

  static apercu (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.uid + '/appercu')}, {
      width: 900
    })
  }

  static store (event) {
    event.preventDefault()
    var form = $(event.target)[0]
    var data = new window.FormData(form)
    var btn = $(event.target).find('[type="submit"]')
    var btnHtml = btn.html()
    btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;Traitement en cours...')
    btn.prop('disabled', true)
    var id = $('[name="id"]').val()
    var ajaxParams = {
      id: id,
      type: 'POST',
      url: window.chmSite.url('entretiens/store'),
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
        window.chmModal.alert('<i class="fa fa-check-circle"></i>&nbsp;Opération effectuée', response.message, {width: 415, callback: 'window.chmModal.reload'})
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      window.chmModal.showAlertMessage('danger', message)
    }).always(function () {
      btn.html(btnHtml)
      btn.prop('disabled', false)
    })
  }

  static storeCheckedUsers (event) {
    event.preventDefault()
    var form = $(event.target)[0]
    var data = new window.FormData(form)
    var btn = $(event.target).find('[type="submit"]')
    var btnHtml = btn.html()
    var ids = $('[name="ids"]').val()
    btn.html('<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;Traitement en cours...')
    btn.prop('disabled', true)
    var ajaxParams = {
      ids: ids,
      type: 'POST',
      url: window.chmSite.url('entretiens/storeCheckedUsers'),
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
        window.chmModal.alert('<i class="fa fa-check-circle"></i>&nbsp;Opération effectuée', response.message, {width: 415, callback: 'window.chmModal.reload'})
      }
    }).fail(function (jqXHR, textStatus, errorThrown) {
      var message = jqXHR.status + ' - ' + jqXHR.statusText
      window.chmModal.showAlertMessage('danger', message)
    }).always(function () {
      btn.html(btnHtml)
      btn.prop('disabled', false)
    })
  }

  static delete (params) {
    var token = $('input[name="_token"]').val()
    window.chmModal.show({
      type: 'POST',
      url: window.chmSite.url('entretiens/' + params.eid + '/delete'),
      data: {'_token': token, '_method': 'DELETE'}
    }, {
      message: '<i class="fa fa-trash"></i>&nbsp;Suppression en cours...'
    })
    window.location.href = 'entretiens/index?deleted=1'
  }

  static submission (params) {
    $('.btn-danger').css('color', 'red !important')
    var token = $('input[name="_token"]').val()
    var object = window.chmModal.show({
      type: 'POST',
      url: window.chmSite.url('entretiens/' + params.eid + '/u/' + params.user + '/submit'),
      data: {'_token': token, '_method': 'PUT'}
    }, {
      message: '<i class="fa fa-send"></i>&nbsp;Soumission en cours...'
    })
    object.modal.attr('chm-modal-action', 'reload')
  }

}
