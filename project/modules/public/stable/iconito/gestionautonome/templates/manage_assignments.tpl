{assign var="school" value=$ppo->nodeInfos.parent.ALL}
<h2>
  {if $ppo->filters.mode == 'changeClassroom'}
    {i18n key="gestionautonome|gestionautonome.message.assignementchange}
  {else}
    {i18n key="gestionautonome|gestionautonome.message.preparenextgrade}
  {/if}
</h2>

<a href="{copixurl dest=gestionautonome||showTree}" class="button button-back">{i18n key="gestionautonome|gestionautonome.message.back}</a>


<form action="{copixurl dest="gestionautonome||filterAndDisplayAssignments"}" method="post" id="filter-form">
    <div id="origin" class="filterClass">
        
        <ul class="originTab">
            <li><a href="#originStructure">{i18n key="gestionautonome|gestionautonome.message.searchByStructure}</a></li>
            <li><a href="#originName">{i18n key="gestionautonome|gestionautonome.message.searchByName}</a></li>
        </ul>
        
        <select name="search_mode" id="search-mode" class="hiddenClean">
            <option value="byName">Par nom</option>
            <option value="byStructure" selected>Par structure</option>
        </select>
        
        <div id="originStructure">
            <h3>{i18n key="gestionautonome|gestionautonome.message.origin}</h3>
            {if $ppo->filters.mode == 'changeClassroom'}
              <input type="hidden" name="origin_grade" value="{$ppo->filters.originGrade}" />
            {else}
              <div class="field" id="origin-grade">
                <label>{i18n key="gestionautonome|gestionautonome.message.schoolyear}</label>
                <select name="origin_grade">
                  {foreach from=$ppo->grades item=grade}
                    <option value="{$grade->id_as}"{if $ppo->filters.originGrade == $grade->id_as} selected="selected"{/if}>{$grade->anneeScolaire}</option>
                  {/foreach}
                </select>
              </div>
            {/if}
            {if $ppo->user->testCredential ('basic:admin') || $ppo->user->isDirector || $ppo->user->isAnimator}
              <div class="field" id="origin-citygroup">
                {copixzone process=gestionautonome|filterGroupCity selected=$ppo->filters.originCityGroup with_label=true name=origin_citygroup with_empty=false}
              </div>
              <div class="field" id="origin-city">
                {copixzone process=gestionautonome|filterCity selected=$ppo->filters.originCity city_group_id=$ppo->filters.originCityGroup name=origin_city with_label=true with_empty=false}
              </div>
              <div class="field" id="origin-school">
                {copixzone process=gestionautonome|filterSchool selected=$ppo->filters.originSchool city_id=$ppo->filters.originCity name=origin_school with_label=true with_empty=false}
              </div>
            {else}
              <div class="field" id="origin-school">
                <label>{customi18n key="gestionautonome|gestionautonome.message.%%Structure%%" catalog=$ppo->vocabularyCatalog->id_vc}</label>
                {$ppo->filters.schoolName}
                <input type="hidden" name="origin_school" value="{$ppo->filters.originSchool}" />
              </div>
            {/if}
            <div class="field" id="origin-class">
              {copixzone process=gestionautonome|filterClass selected=$ppo->filters.originClassroom school_id=$ppo->filters.originSchool with_label=true grade=$ppo->filters.originGrade name=origin_classroom with_empty=true label_empty="Toutes" all=true}
            </div>
            <div class="field" id="origin-level">
              {copixzone process=gestionautonome|filterClassLevel selected=$ppo->filters.originLevel school_id=$ppo->filters.originSchool classroom_id=$ppo->filters.originClassroom with_label=true grade=$ppo->filters.originGrade name=origin_level with_empty=true label_empty="Tous" all=true}
            </div>
            <div class="field" id="origin-usertype">
              <label for="origin_usertype">{i18n key="gestionautonome|gestionautonome.message.type"}</label>
              <select class="form" name="origin_usertype" id="origin_usertype">
                <option value="USER_ELE" {if $ppo->filters.originUserType eq "USER_ELE"} selected="selected"{/if}>Élève</option>
                {if isset($ppo->filters.originSchool)}
                  {assign var='hasCredentialTeacherUpdate' value=$ppo->user->testCredential("module:school|`$ppo->filters.originSchool`|teacher|update@gestionautonome")}
                  {if $hasCredentialTeacherUpdate}
                    <option value="USER_ENS" {if $ppo->filters.originUserType eq "USER_ENS"} selected="selected"{/if}>Enseignant</option>
                  {/if}
                {/if}
              </select>
            </div>
            <div class="field" id="origin-lastname">
              <label for="origin_lastname">{i18n key="gestionautonome|gestionautonome.message.lastname"}</label>
              <input type="text" name="origin_lastname" id="origin_lastname" value="{$ppo->filters.originLastname|escape}" />
            </div>
            <div class="field" id="origin-firstname">
              <label for="origin_firstname">{i18n key="gestionautonome|gestionautonome.message.firstname"}</label>
              <input type="text" name="origin_firstname" id="origin_firstname" value="{$ppo->filters.originFirstname|escape}" />
            </div>
        </div>
        
        <div id="originName">
            <h3>{i18n key="gestionautonome|gestionautonome.message.origin}</h3>
            <div class="field" id="origin-usertype-search">
                <label for="origin_usertype_search">{i18n key="gestionautonome|gestionautonome.message.type"}</label>
                <select class="form" name="origin_usertype_search" id="origin_usertype_search">
                    <option value="USER_ELE" label="Elève"{if $ppo->filters.originUserTypeSearch eq "USER_ELE"} selected="selected"{/if}>Elève</option>
                    {if isset($ppo->filters.originSchool)}
                        {assign var='hasCredentialTeacherUpdate' value=$ppo->user->testCredential("module:school|`$ppo->filters.originSchool`|teacher|update@gestionautonome")}
                        {if $hasCredentialTeacherUpdate}
                            <option value="USER_ENS" label="Enseignant"{if $ppo->filters.originUserTypeSearch eq "USER_ENS"} selected="selected"{/if}>Enseignant</option>
                        {/if}
                    {/if}
                </select>
            </div>
            <div class="field" id="origin-lastname-search">
                <label for="origin_lastname_search">{i18n key="gestionautonome|gestionautonome.message.lastname"}</label>
                <input type="text" name="origin_lastname_search" id="origin_lastname_search" value="{$ppo->filters.originLastnameSearch}" />
            </div>
            <div class="field" id="origin-firstname-search">
                <label for="origin_firstname_search">{i18n key="gestionautonome|gestionautonome.message.firstname"}</label>
                <input type="text" name="origin_firstname_search" id="origin_firstname_search" value="{$ppo->filters.originFirstnameSearch}" />
            </div>
        </div>
    </div>
  
  <div id="destination" class="filterClass">
    <h3>{i18n key="gestionautonome|gestionautonome.message.destination}</h3>
    {if $ppo->filters.mode == 'changeClassroom'}
      <input type="hidden" name="destination_grade" value="{$ppo->filters.destinationGrade}" />
    {else}
      <div class="field" id="destination-grade">
        <label>{i18n key="gestionautonome|gestionautonome.message.schoolyear}</label>
        <select name="destination_grade">
          {foreach from=$ppo->grades item=grade}
            {if $grade->id_as >= $ppo->currentGrade->id_as}
              <option value="{$grade->id_as}"{if $ppo->filters.destinationGrade == $grade->id_as} selected="selected"{/if}>{$grade->anneeScolaire}</option>
            {/if}
          {/foreach}
        </select>
      </div>
    {/if}
    
    {if $ppo->user->testCredential ('basic:admin') || $ppo->user->isDirector || $ppo->user->isAnimator}
      <div class="field" id="destination-citygroup">
        {copixzone process=gestionautonome|filterGroupCity selected=$ppo->filters.destinationCityGroup with_label=true name=destination_citygroup with_empty=false}
      </div>
      <div class="field" id="destination-city">
        {copixzone process=gestionautonome|filterCity selected=$ppo->filters.destinationCity city_group_id=$ppo->filters.destinationCityGroup name=destination-citygroup with_label=true name=destination_city with_empty=false}
      </div>
      <div class="field" id="destination-school">
        {copixzone process=gestionautonome|filterSchool selected=$ppo->filters.destinationSchool city_id=$ppo->filters.destinationCity name=destination-city with_label=true with_empty=false name=destination_school}
      </div>
    {else}
      <div class="field" id="destination-school">
        <label>{customi18n key="gestionautonome|gestionautonome.message.%%Structure%%" catalog=$ppo->vocabularyCatalog->id_vc}</label>
        {$ppo->filters.schoolName}
        <input type="hidden" name="destination_school" value="{$ppo->filters.destinationSchool}" />
      </div>
    {/if}
    <div class="field" id="destination-class">
      {copixzone process=gestionautonome|filterClass selected=$ppo->filters.destinationClassroom school_id=$ppo->filters.destinationSchool grade=$ppo->filters.destinationGrade name=destination-school with_label=true with_empty=true label_empty="Toutes" name=destination_classroom}
    </div>
    <div class="field" id="destination-level">
      {copixzone process=gestionautonome|filterClassLevel selected=$ppo->filters.destinationLevel school_id=$ppo->filters.destinationSchool classroom_id=$ppo->filters.destinationClassroom with_label=true grade=$ppo->filters.destinationGrade name=destination_level with_empty=true label_empty="Tous"}
    </div>
   </div>
   <input type="submit" value="{i18n key="gestionautonome|gestionautonome.message.refresh"}" class="hiddenClean" />
   <p class="mesgInfo">Pour changer d'affectation, glissez-déposez une personne ou une classe entière de la classe d'origine vers la classe de destination.</p>
</form>

<div id="assignments">
  {copixzone process=gestionautonome|manageAssignments}
</div>


{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
    prepareAssignmentsManagementFilter(
      {/literal}'{copixurl dest=gestionautonome|default|filterAndDisplayAssignments}'{literal},
      {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
      {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
      {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
      {/literal}'{copixurl dest=gestionautonome|default|refreshClassLevelFilter}'{literal}
    );
  });
//]]> 
</script>
{/literal}
