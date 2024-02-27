
function findHeadings(container){
    let headings = [];
    for(let i = 0; i < container.children.length; i++){
        let child = container.children[i];
        if(child.tagName[0] == "H" && parseInt(child.tagName[1]) > 1) {
            if(!child.id)
                child.id = "wa-heading-" + Date.now().toString(36) + Math.random().toString(36).substring(2);
            headings.push({
                title: child.innerHTML,
                idlink: child.id,
                level: parseInt(child.tagName.slice(1)) - 1
            });
        }
        else
            headings.push(...findHeadings(child));
    }
    return headings;
}

function outputHeadings(container, headings){
    let numbers = [];
    headings.forEach(h => {
        if(numbers.length == 0)
            numbers.push({level:1, number:1, parentNumber:null});
        let cur = numbers.at(-1);
        while(h.level < cur.level){
            numbers.pop();
            cur = numbers.at(-1);
        }
        if(h.level > cur.level) {
            while(h.level - cur.level > 1){
                numbers.push(cur = {
                    level: cur.level + 1,
                    number: 1,
                    parentNumber: (cur.parentNumber ? cur.parentNumber + "." : "") + cur.number
                });
            }
            numbers.push(cur = {
                level: cur.level + 1,
                number: 1,
                parentNumber: (cur.parentNumber ? cur.parentNumber + "." : "") + cur.number
            });
        }
        else if(cur.level != 1 || cur.number != 1){
            cur.number++;
        }
        let cnum = (cur.parentNumber ? cur.parentNumber + "." : "") + cur.number;
        let template = document.createElement("template");
        template.innerHTML = `<li data-num="${cnum}" class="wa-headlines-lvl${h.level}">
            <a class="wa-headline-link" href="#${h.idlink}">${h.title}</a>
        </li>`;
        container.append(template.content.children[0]);
    });
}

document.addEventListener("DOMContentLoaded", function(){
   let headings_container = document.querySelector(".wa-headlines");
   let post_container = document.querySelector(".wa-article");
   if(!headings_container || !post_container)
       return;
   let headings = findHeadings(post_container);
   if(!headings || headings.length == 0)
       return;
   outputHeadings(headings_container.querySelector("ol"), headings);
   headings_container.classList.remove("invisible");
   if(!jQuery(headings_container).is(".collapsed"))
       jQuery(headings_container).find("ol").show();
   headings_container.querySelector(".wa-headlines-switch").addEventListener("click", (e) => {
       let container = jQuery(e.target).closest(".wa-headlines");
       let ol = container.find("ol");
       if(!ol.is(":visible")) {
           ol.slideDown(300);
           container.removeClass("collapsed");
       }
       else {
           ol.slideUp(300);
           container.addClass("collapsed");
       }
   });
   document.querySelectorAll(".wa-headline-link").forEach(el => {
      el.addEventListener("click", function(e){
          e.preventDefault();
          let target = document.getElementById(this.getAttribute("href").slice(1));
          if(!target)
              return;
          jQuery([document.documentElement, document.body]).animate({
              scrollTop: jQuery(target).offset().top - 35
          }, 1200);
      });
   });
});