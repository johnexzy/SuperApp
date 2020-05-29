<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Src\Layout;

/**
 * Description of FooterClass
 *
 * @author hp
 */
class FooterClass {
    public function returnFooterLayout() {
        return '
           <div class="container mt-5">
              <footer class="bg-white border-top p-3 text-muted small">
                  <div class="row align-items-center justify-content-between">
                      <div>
                          <span class="navbar-brand mr-2"><strong>ChemLounge</strong></span> Copyright &copy;
                          <script>
                              document.write(new Date().getFullYear())
                          </script>
                          . All rights reserved.
                      </div>
                      <div>
                          Designed by <a target="_blank" class="text-secondary font-weight-bold" href="https://www.twitter.com/Obajohn17/">Johnexzy</a>
                      </div>
                  </div>
              </footer>
          </div>';
    }
}
