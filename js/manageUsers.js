function validatePresetSubmission(event){
    let condition = true;
    const form = event.target;
    const type = form.querySelector("[name=type");
    const ID = form.querySelector("[name=ID]");
    const name = form.querySelector("[name=name]");
    const width = form.querySelector("[name=width]");
    const height = form.querySelector("[name=height]");
    const bitrate = form.querySelector("[name=bitrate]");

    if(type.value == "delete"){
        return true;
    }

    if(!isValidFileName(name.value)){
        if(name.value==""){
            proposedName = "[blank]";
        }else{
            proposedName = name.value;
        }

        alert(proposedName + " isn't a valid preset name!");
        condition = false;
    }

    if(!condition){
        event.preventDefault();
        return false;
    }
    return condition;
    
  
  }
  
  
var isValidFileName=(function(){
    var rg1=/^[^\\/:\*\?"<>\|]+$/; // forbidden characters \ / : * ? " < > |
    var rg2=/^\./; // cannot start with dot (.)
    var rg3=/^(nul|prn|con|lpt[0-9]|com[0-9])(\.|$)/i; // forbidden file names
    return function isValid(fname){
      return rg1.test(fname)&&!rg2.test(fname)&&!rg3.test(fname);
    }
})();

