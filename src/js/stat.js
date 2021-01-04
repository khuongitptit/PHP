const statCustomer = () => {
  return $.ajax({
    type: 'GET',
    url: 'src/php/customer.php',
    data: {action:'STAT_CUSTOMER'},
    success: res => {
      const statData = JSON.parse(res);
      const statProcessed = _(statData).groupBy('id').map((item, id)=>({
        customerName: _.head(item).name,
        customerAvatarURL: _.head(item).avatarURL,
        total: _.sumBy(item,'price')
      })).value();
      console.log("aa",_.take(_.orderBy(statProcessed,['total'], ['desc']),3))
      return _.take(_.orderBy(statProcessed,['total'], ['desc']),3);
    },
    error: err => {
      console.log('err', err);
    },
  });
}