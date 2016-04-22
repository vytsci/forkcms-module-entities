<div class="row">
  <div class="col-md-12">
    <h4>{$lblSEO|ucfirst}</h4>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <h4>{$lblTitles|ucfirst}</h4>
    <div class="form-group">
      <ul class="list-unstyled checkboxTextFieldCombo">
        <li class="checkbox">
          <label for="{$language}PageTitleOverwrite" class="visuallyHidden">
            {$fields.page_title_overwrite}
            <b>{$lblPageTitle|ucfirst}</b>
          </label>
          <p class="text-info">{$msgHelpPageTitle}</p>
          {$fields.page_title}
          {$errors.page_title}
        </li>
      </ul>
    </div>
    {option:fields.navigation_title}
    <div class="form-group">
      <ul class="list-unstyled checkboxTextFieldCombo">
        <li class="checkbox">
          <label for="{$language}NavigationTitleOverwrite" class="visuallyHidden">
            {$fields.page_title_overwrite}
            <b>{$lblNavigationTitle|ucfirst}</b></label>
          <p class="text-info">{$msgHelpNavigationTitle}</p>
          {$fields.navigation_title}
          {$errors.navigation_title}
        </li>
      </ul>
    </div>
    {/option:fields.navigation_title}
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <h4>{$lblMetaInformation|ucfirst}</h4>
    <div class="form-group">
      <ul class="list-unstyled checkboxTextFieldCombo">
        <li class="checkbox">
          <label for="{$language}MetaDescriptionOverwrite" class="visuallyHidden">
            {$fields.meta_description_overwrite}
            <b>{$lblDescription|ucfirst}</b>
          </label>
          <p class="text-info">{$msgHelpMetaDescription}</p>
          {$fields.meta_description}
          {$errors.meta_description}
        </li>
      </ul>
    </div>
    <div class="form-group">
      <ul class="list-unstyled checkboxTextFieldCombo">
        <li class="checkbox">
          <label for="{$language}MetaDescriptionOverwrite" class="visuallyHidden">
            {$fields.meta_keywords_overwrite}
            <strong>{$lblKeywords|ucfirst}</strong>
          </label>
          <p class="text-info">{$msgHelpMetaKeywords}</p>
          {$fields.meta_keywords}
          {$errors.meta_keywords}
        </li>
      </ul>
    </div>
    {option:fields.meta_custom}
    <div class="form-group">
      <label for="{$language}MetaCustom" class="visuallyHidden">{$lblExtraMetaTags|ucfirst}</label>
      <p class="text-info">{$msgHelpMetaCustom}</p>
      {$fields.meta_custom}
      {$errors.meta_custom}
    </div>
    {/option:fields.meta_custom}
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <h4>{$lblURL|ucfirst}</h4>
    <div class="form-group">
      <ul class="list-unstyled checkboxTextFieldCombo">
        <li class="checkbox">
          <label for="urlOverwrite" class="visuallyHidden">
            {$fields.url_overwrite}
            <b>{$lblCustomURL|ucfirst}</b>
          </label>
          <p class="text-info">{$msgHelpMetaURL}</p>
          <span id="urlFirstPart">
            {option:detailUrl}{$detailUrl}{/option:detailUrl}
            {option:!detailUrl}{$SITE_URL}{option:prefixURL}{$prefixURL}{/option:prefixURL}/{/option:!detailUrl}
          </span>
          {$fields.url}
          {$errors.url}
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <h4>{$lblSEO|ucfirst}</h4>
    <div class="form-inline">
      <div class="form-group">
        <p><b>{$lblIndex}</b></p>
        {option:errors.seo_index}
        <div class="alert alert-danger">{$errors.seo_index}</div>
        {/option:errors.seo_index}
        <ul class="list-unstyled inputListHorizontal">
          {iteration:fields.seo_index}
          <li class="radio">
            <label for="{$fields.seo_index.id}">{$fields.seo_index.element} {$fields.seo_index.label}</label>
          </li>
          {/iteration:fields.seo_index}
        </ul>
      </div>
    </div>
    <div class="form-inline">
      <div class="form-group">
        <p><b>{$lblFollow}</b></p>
        {option:errors.seo_follow}
        <div class="alert alert-danger">{$errors.seo_follow}</div>
        {/option:errors.seo_follow}
        <ul class="list-unstyled inputListHorizontal">
          {iteration:fields.seo_follow}
          <li class="radio">
            <label for="{$fields.seo_follow.id}">{$fields.seo_follow.element} {$fields.seo_follow.label}</label>
          </li>
          {/iteration:fields.seo_follow}
        </ul>
      </div>
    </div>
  </div>
</div>
{$fields.meta_id}
{$fields.base_field_name}
{$fields.custom}
{$fields.class_name}
{$fields.method_name}
{$fields.parameters}
