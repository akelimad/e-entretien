import $ from 'jquery'
import trans from './../script/functions'

export default class chmUser {

  static show (params) {
    window.chmModal.show({type: 'GET', url: window.chmSite.url('user/' + params.id)})
  }

  static delete(event, ids) {
    ids = (typeof event[0] !== 'undefined') ? event : ids
    window.chmModal.show({
      type: 'DELETE',
      url: '/user/delete',
      data: {
        "ids": ids,
        "_method": 'DELETE',
        "_token": $('input[name="_token"]').val(),
      }
    }, {
      message: '<i class="fa fa-circle-o-notch fa-spin"></i>&nbsp;' + trans("Suppression en cours..."),
      onSuccess: (response) => {
        if ('status' in response && response.status === 'alert') {
          window.chmTable.refresh('#UsersTableContainer')
        }
      }
    })
  }

}
