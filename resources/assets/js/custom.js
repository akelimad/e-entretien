import $ from 'jquery'
import uuidv4 from 'uuid/v4'

$(document).ready(function () {
  // Check all table rows
  $('.chmTable_checkAll').change(function () {
    $('.chmTable_checkAll').not($(this)).prop('checked', $(this).is(':checked'))
    $('.chmTableCB').prop('checked', $(this).is(':checked'))
  })

  $('.chmTableCB').change(function () {
    $('.chmTable_checkAll').prop('checked', ($('.chmTableCB').length === $('.chmTableCB:checked').length))
  })

  $('.chmTableBulkActionsSubmitButton').click(function (event) {
    var selected = $(this).prev('select').find('option:selected')
    if ($(this).closest('form').find('.chmTableCB:checked').length === 0) {
      event.preventDefault()
      window.chmModal.alert('', 'Vous devez choisir au moin une ligne.', { width: 300 })
    } else if (selected.val() === '') {
      event.preventDefault()
      window.chmModal.alert('', 'Vous devez choisir une action.', {width: 300})
    } else if (selected.data('action') !== 'undefined') {
      event.preventDefault()
      var checked = []
      $('.chmTableCB:checked').each(function (k, v) {
        checked[k] = $(this).val()
      })
      var parts = selected.data('action').split('.')
      if (parts.length === 2) {
        window[parts[0]][parts[1]](checked)
      } else {
        window[parts[0]](checked)
      }
    }
  })

  // bulk send invitations
  $('body').on('submit', '#invitForm', function (event) {
    event.preventDefault()
    $('#sendInvitButton').prop('disabled', true)
    var forumId = $(this).find('select>option:selected').val()
    var cids = $(this).find('[name="cids[]"]')
    if (forumId === '') return
    var candidats = []
    $.each(cids, function (k, v) {
      candidats[k] = $(this).val()
    })
    $('#sendInvitButton').html('<i class="fa fa-circle-o-notch fa-spin fa"></i>&nbsp;Envoi en cours... <span class="badge badge-default">0/' + candidats.length + '</span>')
    window.chmCandidat.sendInvitationLoop(candidats, forumId)
  })

  // Change perpe value
  $('.chmTablePerpage').change(function () {
    var perpage = $(this).val()
    window.chmUrl.setParam('page', 1)
    window.chmUrl.setParam('perpage', perpage)
    window.location.reload()
  })

  // Modal close event
  $('body').on('hidden.bs.modal', '.chm-modal', function () {
    if ($(this).attr('chm-modal-action') === 'reload') {
      window.location.reload()
    } else {
      $(this).data('bs.modal', null)
      $(this).remove()
    }
  })

  // Prevent default on click event
  $('a[onclick]').on('click', function (e) {
    return e.preventDefault()
  })

  // Initialise filter form
  window.chmFilter.init()

  $('body').on('click', '.select2 option[value="all"]', function () {
    var $select = $(this).closest('select')
    $($select).find('option').not($(this)).prop('selected', $(this).is(':checked'))
    $($select).trigger('change')
  })

  $('body').on('click', '.addLine', function (event) {
    event.preventDefault()
    var copy = $('#addLine-wrap').find('.form-group:first').clone()
    copy.find('input').val('')
    copy.find('button').toggleClass('addLine deleteLine')
    copy.find('button>i').toggleClass('fa-plus fa-minus')
    var uid = uuidv4()
    $.each(copy.find('input'), function () {
      var name = $(this).attr('name')
      $(this).attr('name', name.replace('[0]', '[' + uid + ']'))
    })
    $('#addLine-wrap').append(copy)
  })

  $('body').on('click', '.deleteLine', function () {
    $(this).closest('.form-group').remove()
  })

  $('body').on('change', '#questionType', function () {
    window.showHideChoiceFields()
  })
})

window.showHideChoiceFields = () => {
  var value = $('#questionType').val()
  if (value === 'radio' || value === 'checkbox') {
    $('#addLine-wrap').show()
    $('#addLine-wrap #choiceField').prop('required', true).attr('name', 'subQuestions[0]')
  } else {
    $('#addLine-wrap').hide()
    $('#addLine-wrap #choiceField').prop('required', false).attr('name', '')
  }
}
