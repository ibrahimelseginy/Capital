<?php
require_once __DIR__.'/../lib/home.php';

function home_editor_item(array $fields, array $item, int $index, bool $removable=true): void
{ ?>
<details class="home-editor-item"><summary><span data-item-title><?= home_escape($item['title']??$item['name']??$item['label']??$item['text']??('عنصر '.($index+1))) ?></span></summary>
  <div class="home-editor-item-body"><?php home_editor_fields($fields,$item); ?><?php if ($removable): ?><button type="button" class="btn btn-sm admin-delete-button home-remove" data-home-remove>حذف العنصر</button><?php endif; ?></div>
</details>
<?php }

function home_editor_fields(array $fields, array $content): void
{
    static $serial=0;
    // Use the same compact row for section controls and every nested card.
    $hasItemControls=isset($fields['sort_order'],$fields['is_active']);
    if ($hasItemControls) {
        // DOM order follows the RTL row: order on the right, then visibility.
        $fields=array_diff_key($fields,['sort_order'=>true,'is_active'=>true]) + ['sort_order'=>$fields['sort_order'],'is_active'=>$fields['is_active']];
    }
    echo '<div class="home-editor-fields">';
    foreach ($fields as $key=>$field):
        $value=$content[$key]; $id='home-field-'.(++$serial);
        if ($field['type']==='hidden') {
            echo '<div data-field="'.home_escape($key).'" data-type="hidden" hidden><input data-input type="hidden" value="'.home_escape((string)$value).'"></div>';
            continue;
        }
        $display=$field['type']==='select'?($field['options'][$value]??''):(is_scalar($value)?(string)$value:'');
        $width=min(44,max(16,mb_strlen($display)+4));
        if ($hasItemControls && $key==='sort_order') echo '<div class="home-item-controls" role="group" aria-label="الترتيب والإظهار">'; ?>
    <div class="field home-field <?= in_array($field['type'],['list','textarea'],true)?'home-field-wide':'' ?>" data-field="<?= home_escape($key) ?>" data-type="<?= home_escape($field['type']) ?>" style="--home-field-width:<?= $width ?>ch">
      <?php if ($field['type']==='list'): ?>
        <div class="home-list-head"><h4><?= home_escape($field['label']) ?></h4><?php if (empty($field['fixed'])): ?><button type="button" class="btn btn-soft btn-sm" data-home-add>+ إضافة عنصر</button><?php endif; ?></div>
        <?php $itemTypes=array_column($field['fields'],'type'); $layout=in_array('list',$itemTypes,true)?'nested':(count($field['fields'])===3?'compact':'cards'); ?>
        <div class="home-editor-items home-editor-items-<?= $layout ?>" data-max="<?= (int)$field['max'] ?>"><?php foreach ($value as $i=>$item) home_editor_item($field['fields'],$item,$i,empty($field['fixed'])); ?></div>
        <?php if (empty($field['fixed'])): ?><template data-home-template><?php home_editor_item($field['fields'],home_default_fields($field['fields']),0); ?></template><?php endif; ?>
      <?php elseif ($field['type']==='checkbox'): ?>
        <label class="auth-check" for="<?= $id ?>" title="<?= home_escape($field['label']) ?>"><input id="<?= $id ?>" data-input type="checkbox" aria-label="<?= home_escape($field['label']) ?>" <?= $value?'checked':'' ?>><span><?= home_escape($hasItemControls && $key==='is_active'?'إظهار':$field['label']) ?></span></label>
      <?php else: ?>
        <label class="label" for="<?= $id ?>"><?= home_escape($field['label']) ?></label>
        <?php if ($field['type']==='textarea'): ?><textarea id="<?= $id ?>" data-input class="textarea" maxlength="<?= $field['max'] ?>" rows="2"><?= home_escape($value) ?></textarea>
        <?php elseif ($field['type']==='select'): ?><select id="<?= $id ?>" data-input class="select"><?php foreach ($field['options'] as $opt=>$label): ?><option value="<?= home_escape((string)$opt) ?>" <?= $value===$opt?'selected':'' ?>><?= home_escape($label) ?></option><?php endforeach; ?></select>
        <?php else: ?><input id="<?= $id ?>" data-input class="input <?= in_array($field['type'],['url','date','number'],true)?'ltr-input':'' ?>" type="<?= in_array($field['type'],['number','date'],true)?$field['type']:'text' ?>" value="<?= home_escape((string)$value) ?>" <?= $field['type']==='number'?'min="0" max="'.$field['max'].'" step="1" required':'maxlength="'.($field['max']??250).'"' ?>><?php endif; ?>
      <?php endif; ?>
    </div>
    <?php if ($hasItemControls && $key==='is_active') echo '</div>'; endforeach; echo '</div>';
}
