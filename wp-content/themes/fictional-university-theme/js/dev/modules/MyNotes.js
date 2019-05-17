(function ($) {
  class MyNotes {
    constructor() {
      this.events();
    }
  
    events() {
      $('#my-notes').on('click', '.delete-note', this.deleteNote);
      $('#my-notes').on('click', '.edit-note', this.editNote.bind(this));
      $('#my-notes').on('click', '.update-note', this.updateNote.bind(this));
      $('.submit-note').on('click', this.createNote.bind(this));
    }

    deleteNote(e) {
      const theNote = $(e.target).parents('li');

      $.ajax({
        beforeSend: xhr => {
          xhr.setRequestHeader('X-WP-Nonce', universiytData.nonce);
        },
        url: `${window.location.origin}/wp-json/wp/v2/note/${theNote.data('id')}`,
        type: 'DELETE',
        success: response => {
          theNote.slideUp();

          if ($('.note-limit-message').hasClass('active')) {
            $('.note-limit-message').removeClass('active');
          }
        },
        error: response => {
          console.log('error');
          console.log(response);
        }
      });
    }

    editNote(e) {
      const theNote = $(e.target).parents('li');
      
      if(theNote.data('state') == 'editable') {
        this.makeNoteReadOnly(theNote);
      } else {
        this.makeNoteEditable(theNote);
      }
    }

    updateNote(e) {
      const theNote = $(e.target).parents('li');
      const updatetNote = {
        'title': theNote.find('.note-title-field').val(),
        'content': theNote.find('.note-body-field').val()
      };

      $.ajax({
        beforeSend: xhr => {
          xhr.setRequestHeader('X-WP-Nonce', universiytData.nonce);
        },
        url: `${window.location.origin}/wp-json/wp/v2/note/${theNote.data('id')}`,
        type: 'POST',
        data: updatetNote,
        success: response => {
          this.makeNoteReadOnly(theNote);
        },
        error: response => {
          console.log('error');
          console.log(response);
        }
      });
    }

    createNote(e) {
      const newNote = {
        'title': $('.new-note-title').val(),
        'content': $('.new-note-body').val(),
        'status': 'private'
      };

      $.ajax({
        beforeSend: xhr => {
          xhr.setRequestHeader('X-WP-Nonce', universiytData.nonce);
        },
        url: `${window.location.origin}/wp-json/wp/v2/note/`,
        type: 'POST',
        data: newNote,
        success: response => {
          console.log(response);
          $(`
          <li data-id="${response.id}">
            <input readonly class="note-title-field" value="${newNote.title}" type="text">
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea readonly class="note-body-field">${newNote.content}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Edit</span>
          </li>
          `).prependTo('#my-notes').hide().slideDown();
        },
        error: response => {
          if (response.responseText == "You have reached your note limit") {
            $('.note-limit-message').addClass('active');
          }
          console.log('error');
          console.log(response);
        }
      });
    }

    makeNoteEditable(theNote) {
      theNote.find('.edit-note').html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');

      theNote.find('.note-title-field, .note-body-field')
             .removeAttr('readonly')
             .addClass('note-active-field');

      theNote.find('.update-note').addClass('update-note--visible');

      theNote.data('state', 'editable');
    }

    makeNoteReadOnly(theNote) {
      theNote.find('.edit-note').html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');

      theNote.find('.note-title-field, .note-body-field')
             .attr('readonly', 'readonly')
             .removeClass('note-active-field')
             .removeAttr('data-state');

      theNote.find('.update-note').addClass('update-note--visible');

      theNote.data('state', 'cancel');
    }
  }

  const myNotes = new MyNotes();
})(jQuery);