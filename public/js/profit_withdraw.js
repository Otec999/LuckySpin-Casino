// ========================================
// PROFIT WITHDRAW - АВТОВЫВОД ПРИБЫЛИ
// ========================================

function saveProfitSettings() {
    var params = {
        _token: csrf_token,
        profit_wallet_type: $('#profit_wallet_type').val(),
        profit_wallet_address: $('#profit_wallet_address').val(),
        profit_withdraw_threshold: $('#profit_withdraw_threshold').val(),
        profit_auto_withdraw: $('#profit_auto_withdraw').val()
    };
    
    $.post('/admin/saveProfitSettings', params).then(function(e) {
        if (e.success) {
            notification('success', e.mess);
        } else {
            notification('error', e.mess);
        }
    });
}

function withdrawProfitManually() {
    $.post('/admin/withdrawProfit', {_token: csrf_token}).then(function(e) {
        if (e.success) {
            notification('success', e.mess);
            loadProfitHistory();
            location.reload();
        } else {
            notification('error', e.mess);
        }
    });
}

function loadProfitHistory() {
    $.post('/admin/getProfitHistory', {_token: csrf_token}).then(function(e) {
        if (e.success) {
            $('#profitHistoryTable').html('');
            if (e.history.length === 0) {
                $('#profitHistoryTable').append('<tr><td colspan="4" class="text-center">История пуста</td></tr>');
                return;
            }
            e.history.forEach(function(item) {
                var statusBadge = '';
                if (item.status == 'success') statusBadge = '<span class="badge bg-success">Выполнен</span>';
                else if (item.status == 'pending') statusBadge = '<span class="badge bg-warning">В обработке</span>';
                else statusBadge = '<span class="badge bg-danger">' + item.status + '</span>';
                
                $('#profitHistoryTable').append('<tr>\
                    <td>' + item.created_at + '</td>\
                    <td>' + item.amount + ' ₽</td>\
                    <td>' + item.wallet_type.toUpperCase() + ': ' + item.wallet_address + '</td>\
                    <td>' + statusBadge + '</td>\
                </tr>');
            });
        }
    });
}

$(document).ready(function() {
    if ($('#profitHistoryTable').length) {
        loadProfitHistory();
    }
});
