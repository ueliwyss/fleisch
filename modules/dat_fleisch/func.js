function printRechnung(url) {
      if(confirm("Möchten Sie diese Rechnungen wirklich drucken?\nWarnung: Wenn Sie die Rechnungen drucken, werden sie im System als verschickt registriert,\nwas sich auf das erneute verschicken der Rechnung als Mahnung auswirkt.\nJedoch kann das Versanddatum nachträglich geändert werden.")) {
          document.getElementById('pdf').src=url;
          
      }
}