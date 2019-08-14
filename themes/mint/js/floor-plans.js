(function(){
    "use strict";
    var togglesContainer=$("#checkboxes"),
        checkboxesContainer=$("#checkboxes-container"),
        floor_plans=null,
        formatted_floor_plans=[],
        initTableSorter=false,
        isDetailOpen=false,
        mobileCategoryLabel=$("#mobile-label"),
        mobileCategoryOverlay=$("#mobile-category-overlay"),
        searchDate,
        searchDateTime,
        searchForm=$("#search-form"),
        searchFormInputs=searchForm.find("input"),
        selectedToggle="ALL",
        showFP,
        startDate,
        todaysDate = new Date(),
        todaysDateTime,
        unitDetail=$("#unit-detail"),
        unitDetailImg=unitDetail.find("img"),
        unitDetailWrapper=$("#unit-detail-wrapper"),
        units=null,
        unit_types=[],
        valuesArray=[];
    todaysDate.setHours(0,0,0,0);
    todaysDateTime = todaysDate.getTime();
    function checkExists(checkObj){
        return (typeof checkObj != "undefined");
    }
    function numberCommas(num){
        if (typeof num != undefined && num != null){
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }else{
            return '';
        }
    }
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
    function submitForm(){
        getParameters();
        return false;
    }
    function getParameters(){
        var parameters = searchFormInputs.serializeArray();
        valuesArray=[];
        $.map(parameters,function(obj,i){
            var objName=obj.name.replace('[]','');
            if (typeof valuesArray[objName] == 'undefined'){
                valuesArray[objName]=[];
            }
            valuesArray[objName].push(obj.value);
        });
        searchDate = new Date(valuesArray["move-in-date"][0]);
        if (searchDate.getTime() != searchDateTime){ // if new searchDate, pull new unit data
            searchDateTime = searchDate.getTime();
            getUnitsJSON();
        }else{
            displayUnitTypes(); // otherwise filter existing data
        }
    }
    function compareDateToSearch(compareDate){
        var availableDate = new Date(compareDate);
        availableDate = (availableDate.getTime() < todaysDateTime) ? todaysDateTime : availableDate.getTime();
        return availableDate <= searchDateTime;
    }
    //updates history, and share button URLs for floor plan details - allows social media sharing of individual floor plans
    function pushHistory(unitTypeID){
        if (unitTypeID != ""){
            unitTypeID = "#" + unitTypeID;
        }
        var newURL=floorPlansURL+unitTypeID;
        $('#share-buttons span').each(function(){
            var attr=$(this).attr('st_url');
            if (typeof attr !== typeof undefined && attr !== false) {
                $(this).attr('st_url',newURL);
            }
        });
        document.location.hash=unitTypeID;
    }
    function checkParameters(unitType){
        //first check unit type
        if (checkExists(valuesArray["unit-type"]) &&
            valuesArray["unit-type"][0] != "" &&
            valuesArray["unit-type"][0] != "0" && // check if selected type is "ALL"
            checkExists(unitType.FloorPlanGroupName)) {
            var meetsType = false;
            // cycle through the multidimensional subarrays of unit types to check if unit matches selected, grouped types
            for (var j = 0; j < valuesArray["unit-type"].length; j++) {
                var currentUnitType=valuesArray["unit-type"][j];
                for (var k in unit_types[currentUnitType]){
                    if (unit_types[currentUnitType][k] == unitType.FloorPlanGroupName) {
                        meetsType = true;
                    }
                }
            }
            if (!meetsType) {
                return false;
            }
        }
        if (checkExists(unitType.units)){
            var unitsMeetType=[];
            for (var i=0;i<unitType.units.length;i++){
                var unit=unitType.units[i];
                var unitMeetsParameters=true;
                // first check if unit is occupied
                if (checkExists(unit.Availability.MadeReadyDate)) {
                    //then check various search parameters
                    //move-in date
                    unitMeetsParameters=compareDateToSearch(unit.Availability.MadeReadyDate);
                    //max rent
                    if (checkExists(valuesArray["max-rent"]) &&
                        valuesArray["max-rent"][0] != "" &&
                        checkExists(unit.BaseRentAmount) &&
                        (parseInt(unit.BaseRentAmount) >=  valuesArray["max-rent"][0])){
                        unitMeetsParameters=false;
                    }
                }else{
                    unitMeetsParameters=false;
                }
                unitsMeetType.push(unitMeetsParameters);
            }
            for (var k=0;k<unitsMeetType.length;k++){
                if (unitsMeetType[k]){
                    return true;
                }
            }
        }
        return false;
    }
    function getFloorPlanByID(fpID){
        for (var i=0;i<floor_plans.length;i++){
            if (checkExists(floor_plans[i].FloorPlanID) && floor_plans[i].FloorPlanID == fpID){
                return floor_plans[i];
            }
        }
    }
    function toggleShareDiv(){
        $("#share-info-wrapper").fadeToggle();
    }
    function displayUnit(unit){
        var aptNum=(checkExists(unit.Address) && checkExists(unit.Address.UnitNumber)) ? unit.Address.UnitNumber : "",
            moveInDate = $("#move-in-date").val(),
            rent=checkExists(unit.BaseRentAmount) ? numberCommas(parseInt(unit.BaseRentAmount)) : "",
            sqft=(checkExists(unit.UnitDetails) && checkExists(unit.UnitDetails.RentSqFtCount)) ? unit.UnitDetails.RentSqFtCount : "",
            unitID=(checkExists(unit.Address) && checkExists(unit.Address.UnitID)) ? unit.Address.UnitID : "",
            unitTemplate=$("#available-unit-template").clone();
        if (checkExists(aptNum)){
            if (aptNum.substring(0,1)=="0"){
                aptNum=aptNum.substring(1);
            }
            unitTemplate.attr({"id":"unit-"+aptNum});
            unitTemplate.find(".apt span").html(aptNum);
            unitTemplate.css("display","block");
        }
        if (checkExists(rent)){
            unitTemplate.find(".rent span").html("$"+rent);
        }
        if (checkExists(sqft)){
            unitTemplate.find(".sqft span").html(numberCommas(sqft));
        }
        if (checkExists(unitID)){
            if (moveInDate.length == 0){
                var dd = searchDate.getDate();
                var mm = searchDate.getMonth()+1; //January is 0!
                var yyyy = searchDate.getFullYear();
                if(dd<10) {
                    dd='0'+dd
                }
                if(mm<10) {
                    mm='0'+mm
                }
                moveInDate = mm+'/'+dd+'/'+yyyy;
            }
            if (typeof leaseOnlineURL != "undefined"){
                var leasingURL = checkExists(unitID) ? leaseOnlineURL + "?MoveInDate=" + moveInDate + "&UnitId=" + unitID + "&SearchUrl=" + document.location : "";
                unitTemplate.find(".availability a").attr("href",leasingURL);
            }
        }
        return unitTemplate;
    }
    function sizeDetailImage(){
        var imgContainer = unitDetail.find(".left"),
            imgContainerH = imgContainer.height(),
            imgContainerW = imgContainer.width();
        unitDetailImg.css({
            "max-height":imgContainerH,
            "max-width":imgContainerW
        });
    }
    function positionDetailImage(){
        if (isDetailOpen && ($("#header").css("position")==="fixed")){
            var scrollT=$(document).scrollTop(),
                targetT=scrollT-unitDetailWrapper.offset().top+$("#header").height()-20;
            if (targetT<0){
                targetT=0;
            }
            if ((targetT+unitDetailImg.height())<(unitDetailWrapper.outerHeight()-70)){
                unitDetailImg.css("top",targetT);
            }
        }else{
            unitDetailImg.css("top","");
        }
    }
    function sizeUnitTypeDetail(){
        var leftH=unitDetail.find(".left img").outerHeight()+20,
            rightH=unitDetail.find(".right").outerHeight(),
            maxH=(leftH>rightH) ? leftH : rightH;
        if (maxH > 600){
            unitDetailWrapper.css("height",maxH);
        }else{
            unitDetailWrapper.css("height","");
        }
    }
    function displayUnitTypeDetail(unitType){
        var availableCount=unitType.unitsAvailable,
            availableUnitsContainer=$("#available-units"),
            availableUnitElements=[],
            baths=unitType.Bathrooms,
            beds=unitType.Bedrooms,
            den="",
            ID=unitType.FloorPlanID,
            name=unitType.FloorPlanName,
            rent=(unitType.RentMin != unitType.RentMax)? numberCommas(parseInt(unitType.RentMin)) + " - " + numberCommas(parseInt(unitType.RentMax)) : numberCommas(parseInt(unitType.RentMax)),
            sqft=unitType.RentableSquareFootage,
            type=unitType.FloorPlanGroupName;
        //update history
        pushHistory(ID);
        //set flag
        isDetailOpen=true;
        if (checkExists(name)) {
            var fileName=name.toUpperCase();
            unitDetail.find("img").attr("src",templateURL+"/images/floor-plans/MINT_"+fileName+".jpg");
            $("#unit-pdf-link").attr("href",templateURL+"/pdfs/"+fileName+".pdf");
            unitDetail.find(".name").html(name);
        }
        if (checkExists(beds)){
            var bedString=(beds>0)? "<span>"+beds+"</span> Bed" : "Studio";
            if (checkExists(type)){
                type=type.toLowerCase();
                den=(type.indexOf("den") > -1) ? "Den" : "";
                if (den != ""){
                    bedString += " with Den";
                }
            }
            unitDetail.find(".beds").html(bedString);
        }
        if (checkExists(baths)){
            unitDetail.find(".baths span").html(baths);
        }
        if (checkExists(sqft)){
            unitDetail.find(".sqft span").html(numberCommas(sqft));
        }
        if (checkExists(rent)){
            unitDetail.find(".rent span").html("$"+rent);
        }
        unitDetail.find(".available-count span").html(availableCount);
        //loop through units and display each
        availableUnitsContainer.html("");
        if (checkExists(unitType.units) && availableCount>0){
            for (var i=0;i<unitType.units.length;i++){
                var unit=displayUnit(unitType.units[i]);
                availableUnitElements.push(unit[0]);
            }
            availableUnitsContainer.append(availableUnitElements).show();
        }else{
            availableUnitsContainer.hide();
        }
        unitDetailWrapper.fadeIn(function(){
            //set outer container height based on right content height
            // sizeUnitTypeDetail();
            sizeDetailImage();
            //scroll to unit detail
            $("html,body").animate({scrollTop:0},250);
        });
        $("#unit-detail-background").fadeIn();
    }
    function hideUnitTypeDetail(){
        //var unitDetail=$("#unit-detail");
        $("#unit-detail-background").fadeOut();
        $("#unit-detail-wrapper").fadeOut();
        pushHistory("");
        //set flag
        isDetailOpen=false;
    }
    function displayUnitType(unitType){
        //clone #result-template
        var availability=unitType.unitsAvailable,
            baths=unitType.Bathrooms,
            beds=unitType.Bedrooms,
            den="",
            name=unitType.FloorPlanName,
            rent=(unitType.RentMin != unitType.RentMax)? numberCommas(parseInt(unitType.RentMin)) + " - " + numberCommas(parseInt(unitType.RentMax)) : numberCommas(parseInt(unitType.RentMax)),
            sqft=unitType.RentableSquareFootage,
            type=unitType.FloorPlanGroupName,
            unitRow=$("#result-template").clone();
        //type
        if (checkExists(type)){
            type=type.toLowerCase();
            unitRow.attr({"id":"unit-type-"+name,"class":"unit-type-tr"});
            unitRow.find(".name").html(name);
            den=(type.indexOf("den") > -1) ? "Den" : "";
        }
        //bedbath
        if (checkExists(beds)){
            var bedbath=(beds>0)? beds+" Bed" : "Studio";
            if (checkExists(baths) && baths > 0){
                bedbath += " / "+baths+" Bath";
            }
            unitRow.find(".bedbath").html(bedbath);
        }
        //den
        if (den != ""){
            unitRow.find(".den").html(den);
        }
        //sqft
        if (checkExists(sqft)){
            unitRow.find(".sqft").html(numberCommas(sqft));
        }
        //rent
        if (checkExists(rent)){
            unitRow.find(".rent").html("$"+rent);
        }
        //availability
        if (availability > 0){
            unitRow.find(".availability").html(availability+" Available");
        }
        //action
        unitRow.find("button").on({
            "click":function(){
                displayUnitTypeDetail(unitType);
            }
        });
        return unitRow;
    }
    function displayUnitTypes(){
        var checkshowFP=false,
            foundResults=false,
            hideLoader=true,
            resultsTable=$("#results-table"),
            results = $("#results"),
            noResults=$("#no-results"),
            unitTypeElements=[];
        resultsTable.html(" ");
        if (showFP.length>0){
            checkshowFP=true;
        }
        for (var fp in floor_plans){
            if (checkshowFP && floor_plans[fp].FloorPlanID == showFP){
                displayUnitTypeDetail(floor_plans[fp]);
                foundResults=true;
            }
            var checkP=checkParameters(floor_plans[fp]);
            if (checkP !== false){
                var unitType=displayUnitType(floor_plans[fp]);
                unitTypeElements.push(unitType[0]);
                foundResults=true;
            }
            if (hideLoader){
                $("#loader").fadeOut();
                hideLoader=false;
            }
        }
        if(foundResults){
            if (noResults.length) {
                noResults.hide();
            }
            results.fadeIn();
            $.fn.append.apply(resultsTable, unitTypeElements).html();
            if (!initTableSorter){
                $("#results").tablesorter({sortList:[[0,0]]});
                initTableSorter=true;
            }else{
                $("#results").trigger("update");
            }
        }else{
            //if no results available, check if there's a no results message
            results.hide();
            if (noResults.length){
                //show the no results message
                noResults.fadeIn();
            }
        }
    }
    function setFormFilters(){
        searchFormInputs=searchForm.find("input");
        searchFormInputs.unbind('change keyup paste');
        searchFormInputs.on('change keyup paste',function(){
            submitForm();
        });
    }
    function interpretTypeClass(type){
        switch(type){
            case "eff":
                return "studio";
                break;
            default:
                return type;
                break;
        }
    }
    function int2string(int){
        var strings=["zero","one","two","three","four","five","six","seven","eight","nine"];
        return strings[int];
    }
    //hardcode group names
    function interpretTypeName(type){
        var type2=type.toLowerCase(),
            name=int2string(type2.substring(0,1));
        if (type2.indexOf("eff") > -1){
            name = "Studio";
        }else if (type2.indexOf("1bd") > -1){
            name = "1BR";
        }else if (type2.indexOf("2bd") > -1){
            name = "2BR";
        }else if (type2.indexOf("3bd") > -1){
            name = "3BR";
        }else if (type2.indexOf("all") > -1){
            name = "All";
        }
        return name;
    }
    function createUnitTypeToggle(typeIndex,checked){
        var type=unit_types[typeIndex][0],
            toggle=$(document.createElement("input")),
            toggleContainer=$(document.createElement("div")),
            toggleLabel=$(document.createElement("label")),
            containerClass="checkbox-container",
            typeClass=type.split("/"),
            typeID=type.replace(/\//g, "-");
        toggle.attr({"id":typeID,"type":"radio","name":"unit-type","value":typeIndex});
        if (checked){
            toggle.attr({"checked":"checked"});
            toggleContainer.addClass("active");
        }
        toggle.on({
            "click":function(){
                var toggles=togglesContainer.find(".checkbox-container");
                toggles.removeClass("active");
                toggleContainer.addClass("active");
                if (mobileCategoryOverlay.is(":visible")){
                    togglesContainer.find("input").not(this).attr("checked",false);
                    mobileCategoryOverlay.trigger("click");
                    mobileCategoryLabel.hide();
                    $(".checkbox-container").removeClass("active");
                    if ($(this).is(":checked")){
                        $(this).parent(".checkbox-container").addClass("active");
                    }else{
                        mobileCategoryLabel.show();
                    }
                }
                selectedToggle=type;
            }
        });
        toggleLabel.html(interpretTypeName(type));
        // toggleLabel.html(type);
        toggleLabel.attr({"for":typeID});
        if (typeClass.length > 0){
            var typeClassName="type-"+interpretTypeClass(typeClass[0].toLowerCase());
            containerClass += " "+typeClassName;
            toggleLabel.addClass(typeClassName);
        }
        toggleContainer.addClass(containerClass);
        toggleContainer.append(toggle,toggleLabel);
        return toggleContainer;
    }
    function createUnitTypeToggles(){
        togglesContainer.html("");
        var toggleElements=[];
        /*var allToggle=createUnitTypeToggle("ALL",(selectedToggle=="ALL"),toggleColor);
         toggleElements.push(allToggle[0]);*/
        unit_types.unshift(["ALL"]);
        for (var i in  unit_types){
            if (unit_types[i].length > 0){
                var toggle=createUnitTypeToggle(i,(selectedToggle==unit_types[i][0]));
                toggleElements.push(toggle[0]);
            }
        }
        togglesContainer.append(toggleElements);
        setFormFilters();
    }
    function getUnitsByFloorPlanName(fpName){
        var floor_plan_units=[];
        for (var i=0;i<units.length;i++){
            if (typeof units[i].FloorPlan != "undefined" && typeof units[i].FloorPlan.FloorPlanName != "undefined" && units[i].FloorPlan.FloorPlanName == fpName){
                floor_plan_units.push(units[i]);
            }
        }
        return floor_plan_units;
    }
    function formatUnitTypes(){
        for (var i=0;i<floor_plans.length;i++) {
            if (typeof floor_plans[i].FloorPlanID != "undefined") {
                var fpName=floor_plans[i].FloorPlanName;
                //count the number of available units
                var floor_plan_units=getUnitsByFloorPlanName(fpName),
                    unitsAvailable=0;

                if (floor_plan_units.length>0){
                    for (var j=0;j<floor_plan_units.length;j++){
                        if (typeof floor_plan_units[j].Availability.MadeReadyDate != "undefined" && compareDateToSearch(floor_plan_units[j].Availability.MadeReadyDate)){
                            unitsAvailable+=1;
                        }
                    }
                }
                floor_plans[i].unitsAvailable=unitsAvailable;
                floor_plans[i].units=floor_plan_units;
                //account for duplicate floor plans
                if (typeof formatted_floor_plans[fpName] == "undefined"){
                    formatted_floor_plans[fpName]=floor_plans[i];
                }
            }
        }
        setUnitTypes();
        displayUnitTypes();
    }
    function setUnitTypes(){
        for (var i in formatted_floor_plans) {
            if (typeof formatted_floor_plans[i].FloorPlanGroupName != "undefined") {
                var type = formatted_floor_plans[i].FloorPlanGroupName
                var beds = type.toLowerCase().substring(0,1);
                switch (beds){
                    case "e":
                        beds=0;
                        break;
                    default:
                        beds=parseInt(beds);
                        break;
                }
                if (typeof unit_types[beds] == "undefined"){
                    unit_types[beds]=[];
                }
                if (
                    $.inArray(type,unit_types[beds]) < 0
                    // && formatted_floor_plans[i].unitsAvailable > 0
                    && (
                        typeof formatted_floor_plans[i].units != "undefined"
                        && formatted_floor_plans[i].units.length > 0
                    )
                ){
                    unit_types[beds].push(type);
                }
            }
        }
        createUnitTypeToggles();
    }
    function setupSearch(){
        formatted_floor_plans=[];
        unit_types=[];
        formatUnitTypes();
    }
    function getJSON(){
        $.ajax({
            cache:false,
            url:templateURL+"/JSON/mintRealPageFloorPlans.json",
            dataType:"json",
            success:function(data){
                floor_plans=data;
                if (units != null){
                    setupSearch();
                }
            }
        });
        //get today's preloaded units (from eo4-RealPage-units.json)--but if user searches for another date, pull in results on the fly from API
        $.ajax({
            cache:false,
            url:templateURL+"/JSON/mintRealPageUnits.json",
            dataType:"json",
            success:function(data){
                units=data;
                if (floor_plans != null){
                    setupSearch();
                }
            }
        });
    }
    function getUnitsJSON(){
        $("#results-table").html(" ");
        $("#loader").fadeIn();
        //if user searches for another date, pull in results on the fly from API
        var searchDateStr=searchDate.getFullYear()+"-"+(searchDate.getMonth()+1)+"-"+searchDate.getDate();
        $.ajax({
            cache:false,
            url:"/realPageAPIunitsJSON.php?date=" + searchDateStr,
            dataType:"json",
            success:function(data){
                units=data;
                if (floor_plans != null){
                    setupSearch();
                }
            }
        });
    }
    function initActions(){
        searchForm.on({
            submit:function(){
                return submitForm();
            }
        });
        $("#unit-detail-background,#unit-detail-close-button").on({
            click:function(){
                hideUnitTypeDetail();
            }
        });
        $("#share-button,#share-close-button,#share-info-background").on({
            click:function(){
                toggleShareDiv();
            }
        });
        mobileCategoryOverlay.on("click",function(){
            checkboxesContainer.toggleClass("show");
        });
    }
    $(document).ready(function(){
        showFP=window.location.hash.replace("#","");
        getJSON();
        //set start date to 10/1/17 for datepicker
        startDate = new Date(2017,10,1,0,0,0);
        if (startDate.getTime()<todaysDateTime){
            startDate=todaysDate;
        } else {
            todaysDate=startDate;
        }
        searchDateTime = startDate.getTime();
        initActions();
        $("#move-in-date").datepicker({minDate:todaysDate});
    });
    $(window).on({
        load:function(){
            if (isDetailOpen){
                sizeDetailImage();
            }
        },
        resize:function(){
            if (isDetailOpen){
                sizeDetailImage();
                // positionDetailImage();
                sizeUnitTypeDetail();
            }
        },
        scroll:function(){
            if (isDetailOpen){
                // positionDetailImage();
            }
        }
    });
}());