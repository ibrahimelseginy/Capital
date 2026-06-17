<!-- Asset Action Request Modal -->
<div class="modal-overlay" id="asset-request-modal">
  <div class="modal-content-premium" style="max-width: 560px; padding: 0; overflow: hidden;">
    
    <!-- Header with Gradient & Icon -->
    <div style="background: linear-gradient(135deg, var(--bg-surface) 0%, var(--bg-secondary) 100%); padding: var(--space-6); border-bottom: 1px solid var(--border-default); display: flex; align-items: flex-start; justify-content: space-between;">
      <div class="d-flex gap-4">
        <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-light); color: var(--action-primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(255, 90, 0, 0.15);">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        </div>
        <div>
          <h3 class="text-h4" style="font-weight:var(--weight-bold); color:var(--text-primary); margin: 0 0 4px 0;" id="request-modal-title">
            {{ app()->getLocale() == 'ar' ? 'تقديم طلب تعديل أو حذف' : 'Submit Modification Request' }}
          </h3>
          <p class="text-secondary text-body-sm" style="margin: 0; line-height: 1.5;">
            {{ app()->getLocale() == 'ar' ? 'سيتم إرسال طلبك مباشرة للمسؤول (الآدمن) لمراجعته واتخاذ الإجراء اللازم. يرجى تزويدنا بالتفاصيل بدقة.' : 'Your request will be sent directly to the admin for review and necessary action. Please provide accurate details.' }}
          </p>
        </div>
      </div>
      <button type="button" class="btn btn-ghost btn-icon" onclick="closeRequestModal()" style="border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.04); color: var(--text-secondary); padding: 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <!-- State 1: Form -->
    <div id="request-form-state" style="padding: var(--space-6);">
      <!-- Info Banner -->
      <div style="background: var(--color-info-bg); border: 1px solid var(--color-info); border-radius: var(--radius-lg); padding: var(--space-3) var(--space-4); display: flex; gap: var(--space-3); align-items: center; margin-bottom: var(--space-5);">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="var(--color-info)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <span class="text-body-sm" style="color: var(--text-primary); font-weight: var(--weight-medium);">
          {{ app()->getLocale() == 'ar' ? 'سيتم تعطيل التعديلات المباشرة لحين مراجعة الآدمن.' : 'Direct modifications are disabled until admin review.' }}
        </span>
      </div>

      <div class="form-group-premium" style="margin-bottom: var(--space-4);">
        <label class="form-label-premium" style="font-weight: var(--weight-semibold);">{{ app()->getLocale() == 'ar' ? 'العنصر المستهدف' : 'Target Item' }}</label>
        <div style="position: relative;">
          <input type="text" id="request-item-name" class="form-input-premium" readonly style="background: var(--bg-secondary); border-color: var(--border-default); color: var(--text-primary); font-weight: var(--weight-bold); padding-right: 40px; padding-left: 40px; cursor: not-allowed; opacity: 0.85;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="var(--text-tertiary)" stroke-width="2" style="position: absolute; top: 50%; transform: translateY(-50%); {{ app()->getLocale() == 'ar' ? 'right: 14px;' : 'left: 14px;' }}"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
        </div>
      </div>

      <div class="form-group-premium" style="margin-bottom: var(--space-4);">
        <label class="form-label-premium" style="font-weight: var(--weight-semibold);">{{ app()->getLocale() == 'ar' ? 'نوع الطلب' : 'Request Type' }}</label>
        <select id="request-action-type" class="form-input-premium form-select" onchange="toggleChangesField(this.value)" style="font-weight: var(--weight-medium); cursor: pointer;">
          <option value="edit">{{ app()->getLocale() == 'ar' ? 'طلب تعديل البيانات' : 'Request Edit Data' }}</option>
          <option value="delete">{{ app()->getLocale() == 'ar' ? 'طلب حذف العنصر نهائياً' : 'Request Permanent Delete' }}</option>
        </select>
      </div>

      <div class="form-group-premium" style="margin-bottom: var(--space-4);">
        <label class="form-label-premium" style="font-weight: var(--weight-semibold);">{{ app()->getLocale() == 'ar' ? 'السبب بالتفصيل' : 'Detailed Reason' }}</label>
        <textarea id="request-reason" class="form-input-premium" rows="3" placeholder="{{ app()->getLocale() == 'ar' ? 'يرجى توضيح سبب هذا الطلب بدقة للآدمن...' : 'Please explain the reason clearly to the admin...' }}" required style="resize: vertical; min-height: 80px;"></textarea>
      </div>

      <div class="form-group-premium" id="proposed-changes-group" style="margin-bottom: var(--space-5);">
        <label class="form-label-premium" style="font-weight: var(--weight-semibold);">{{ app()->getLocale() == 'ar' ? 'التعديلات المقترحة' : 'Proposed Modifications' }}</label>
        <textarea id="request-proposed" class="form-input-premium" rows="3" placeholder="{{ app()->getLocale() == 'ar' ? 'مثال: تغيير الحالة من نشط إلى ملغى، أو تحديث القيمة إلى 5000...' : 'Example: Change status to cancelled, or update value to 5000...' }}" style="resize: vertical; min-height: 80px;"></textarea>
      </div>

      <div style="display:flex; gap:16px; margin-top:var(--space-6); padding-top: var(--space-5); border-top: 1px solid var(--border-default);">
        <button class="btn btn-ghost flex-1" style="border-radius:var(--radius-lg); font-weight: var(--weight-semibold);" onclick="closeRequestModal()">{{ app()->getLocale() == 'ar' ? 'إلغاء' : 'Cancel' }}</button>
        <button class="btn btn-primary flex-1" style="border-radius:var(--radius-lg); font-weight: var(--weight-semibold); display: flex; align-items: center; justify-content: center; gap: 8px;" onclick="submitAssetRequest()">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          <span>{{ app()->getLocale() == 'ar' ? 'إرسال الطلب' : 'Submit Request' }}</span>
        </button>
      </div>
    </div>

    <!-- State 2: Processing -->
    <div id="request-loading-state" style="display:none; text-align:center; padding:var(--space-12) var(--space-6);">
      <div class="btn-spinner" style="width:56px; height:56px; border-width:4px; border-top-color:var(--color-primary); margin:0 auto var(--space-5) auto"></div>
      <h3 class="text-h4" style="font-weight:var(--weight-bold); color: var(--text-primary);">
        {{ app()->getLocale() == 'ar' ? 'جاري معالجة الطلب...' : 'Processing Request...' }}
      </h3>
      <p class="text-secondary mt-2">{{ app()->getLocale() == 'ar' ? 'يرجى الانتظار بينما نقوم بإرسال بياناتك بأمان.' : 'Please wait while we securely transmit your data.' }}</p>
    </div>

    <!-- State 3: Success -->
    <div id="request-success-state" style="display:none; text-align:center; padding:var(--space-10) var(--space-6);">
      <div style="width:80px; height:80px; border-radius:50%; background:var(--color-success-bg); color:var(--color-success); display:flex; align-items:center; justify-content:center; margin:0 auto var(--space-5) auto; box-shadow: 0 8px 24px rgba(46, 204, 113, 0.2);">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h2 class="text-h3" style="font-weight:var(--weight-bold); color:var(--text-primary); margin-bottom: 8px;">
        {{ app()->getLocale() == 'ar' ? 'تم إرسال الطلب بنجاح!' : 'Request Sent Successfully!' }}
      </h2>
      <p class="text-secondary text-body" id="request-success-desc" style="max-width: 400px; margin: 0 auto var(--space-8) auto; line-height: 1.6;">
        {{ app()->getLocale() == 'ar' ? 'تم إرسال طلب التعديل/الحذف للمسؤول بنجاح. سيتم إشعارك فور اتخاذ القرار.' : 'Your request was successfully submitted to the admin. You will be notified of their decision.' }}
      </p>
      <button class="btn btn-primary" style="border-radius:var(--radius-lg); min-width: 200px; font-weight: var(--weight-semibold);" onclick="closeRequestModal()">
        {{ app()->getLocale() == 'ar' ? 'إغلاق النافذة' : 'Close Window' }}
      </button>
    </div>
  </div>
</div>

<script>
  let activeRequestItemId = null;
  let activeRequestItemName = "";
  let activeRequestItemType = "";
  let activeRequestAction = "";

  function openRequestModal(itemId, itemName, itemType, actionType) {
    activeRequestItemId = itemId;
    activeRequestItemName = itemName;
    activeRequestItemType = itemType;
    activeRequestAction = actionType;

    document.getElementById('request-form-state').style.display = 'block';
    document.getElementById('request-loading-state').style.display = 'none';
    document.getElementById('request-success-state').style.display = 'none';

    document.getElementById('request-item-name').value = itemName;
    document.getElementById('request-action-type').value = actionType;
    document.getElementById('request-reason').value = "";
    document.getElementById('request-proposed').value = "";

    toggleChangesField(actionType);

    document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    document.getElementById('asset-request-modal').classList.add('open');
  }

  function closeRequestModal() {
    document.getElementById('asset-request-modal').classList.remove('open');
  }

  function toggleChangesField(val) {
    document.getElementById('proposed-changes-group').style.display = val === 'edit' ? 'block' : 'none';
  }

  function submitAssetRequest() {
    const reason = document.getElementById('request-reason').value.trim();
    const proposed = document.getElementById('request-proposed').value.trim();
    const action = document.getElementById('request-action-type').value;
    const isAr = "{{ app()->getLocale() == 'ar' }}";

    if (!reason) {
      alert(isAr ? 'يرجى كتابة السبب أولاً' : 'Please provide a reason');
      return;
    }

    document.getElementById('request-form-state').style.display = 'none';
    document.getElementById('request-loading-state').style.display = 'block';

    setTimeout(() => {
      const requests = JSON.parse(localStorage.getItem('stc_asset_requests')) || [];
      const newReq = {
        id: Date.now(),
        item_id: activeRequestItemId,
        item_title: activeRequestItemName,
        item_type: activeRequestItemType,
        request_type: action,
        reason: reason,
        proposed_changes: action === 'edit' ? proposed : '',
        status: 'Pending',
        created_at: new Date().toISOString().split('T')[0]
      };
      requests.push(newReq);
      localStorage.setItem('stc_asset_requests', JSON.stringify(requests));

      document.getElementById('request-loading-state').style.display = 'none';
      document.getElementById('request-success-state').style.display = 'block';
      document.getElementById('request-success-desc').innerText = isAr
        ? \`تم إرسال طلب \${action === 'edit' ? 'التعديل' : 'الحذف'} للعنصر "\${activeRequestItemName}" بنجاح إلى المسؤول.\`
        : \`Your request to \${action} item "\${activeRequestItemName}" has been successfully sent to the admin.\`;

      if(typeof showToastAlert === 'function') {
        showToastAlert(isAr ? 'تم إرسال الطلب للمسؤول بنجاح' : 'Request submitted successfully');
      }

      // If page has a specific render function, call it
      if(typeof renderProjectsRequests === 'function') renderProjectsRequests();
      if(typeof renderNDARequests === 'function') renderNDARequests();
      if(typeof renderDocumentsRequests === 'function') renderDocumentsRequests();
      if(typeof renderReportsRequests === 'function') renderReportsRequests();
      if(typeof renderConsultationsRequests === 'function') renderConsultationsRequests();
      if(typeof renderEventsRequests === 'function') renderEventsRequests();
    }, 1200);
  }

  // --- Global Dropdown Logic ---
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-actions-wrapper')) {
      document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    }
  });

  function toggleDropdown(e) {
    e.stopPropagation();
    const wrapper = e.target.closest('.dropdown-actions-wrapper');
    const menu = wrapper.querySelector('.dropdown-menu-premium');
    const isVisible = menu.style.display === 'block';
    document.querySelectorAll('.dropdown-menu-premium').forEach(d => d.style.display = 'none');
    if (menu) {
      menu.style.display = isVisible ? 'none' : 'block';
    }
  }
</script>
