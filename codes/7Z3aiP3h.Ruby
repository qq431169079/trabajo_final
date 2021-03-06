                                  rails routes
      Prefix Verb   URI Pattern                   Controller#Action
   companies GET    /companies(.:format)          companies#index
             POST   /companies(.:format)          companies#create
 new_company GET    /companies/new(.:format)      companies#new
edit_company GET    /companies/:id/edit(.:format) companies#edit
     company GET    /companies/:id(.:format)      companies#show
             PATCH  /companies/:id(.:format)      companies#update
             PUT    /companies/:id(.:format)      companies#update
             DELETE /companies/:id(.:format)      companies#destroy
      orders GET    /orders(.:format)             orders#index
             POST   /orders(.:format)             orders#create
   new_order GET    /orders/new(.:format)         orders#new
  edit_order GET    /orders/:id/edit(.:format)    orders#edit
       order GET    /orders/:id(.:format)         orders#show
             PATCH  /orders/:id(.:format)         orders#update
             PUT    /orders/:id(.:format)         orders#update
             DELETE /orders/:id(.:format)         orders#destroy
    products GET    /products(.:format)           products#index
             POST   /products(.:format)           products#create
 new_product GET    /products/new(.:format)       products#new
edit_product GET    /products/:id/edit(.:format)  products#edit
     product GET    /products/:id(.:format)       products#show
             PATCH  /products/:id(.:format)       products#update
             PUT    /products/:id(.:format)       products#update
             DELETE /products/:id(.:format)       products#destroy
       users GET    /users(.:format)              users#index
             POST   /users(.:format)              users#create
    new_user GET    /users/new(.:format)          users#new
   edit_user GET    /users/:id/edit(.:format)     users#edit
        user GET    /users/:id(.:format)          users#show
             PATCH  /users/:id(.:format)          users#update
             PUT    /users/:id(.:format)          users#update
             DELETE /users/:id(.:format)          users#destroy