  window.onload = () => {
    //gestion des lien supprimé
      let links = document.querySelectorAll("[data-delete]")
     //ont  boucle sur links qui est un tableau pour qui a tout les lien qui ont l attri :data-delete
      for(link of links){
          //ont ecoute le click
          link.addEventListener('click', function(e){
              //ont empeche la navigation au moment du click sur le lien
              e.preventDefault()
              //ont demande la confirmation de suppression
              if(confirm('voulez-vous supprimer cette image ?')){
                  //ont envois une requete Ajax vers le href du lien avec la methode DELETE
                  //fetch permet l envois d une requete ajax sous forme de promesse
                  fetch(this.getAttribute("href"),{
                      method: "DELETE",
                      headers: { "X-Requested-with": "XMLHttpRequest","Content-Type":"application/json"},
                      body: JSON.stringify({"_token": this.dataset.token})
                  }).then(
                      //ont recupere la reponse en json  c est aussi une promesse ont
                      // na  deux  reponse possible sa marche ou pas  c est aussi gerer en asymchrone
                      response=>response.json()
                  ).then(data=>{// ont supprimé l element parent donc la div qui est parent de la base a
                      if(data.success){ this.parentElement.remove()}
                      else
                          alert(data.error)
                      //ou ont renvois une exception si sa  echoue
                  }).catch(e=>alert(e))

              }

          })

      }
  }