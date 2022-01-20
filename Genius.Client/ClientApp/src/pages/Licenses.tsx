
import React, { Component } from 'react';


export class Licenses extends Component {
  static displayName = Licenses.name;

  render () {
    return (
      <div className="container -pt-5">
        <div className="row">

          <div className="col-12">
            <h4 className="-font-secondary -fw-700 -pb-3 -reveal">Licenses</h4>
          </div>

          <div className="col-12 -mb-5">
            <div>

              <p className="-reveal">
                Genius - Implementation of an expert system for diagnosing diseases as part of the diploma thesis
                <br/>
                <small>GNU General Public License v3.0 -</small> <a target="_blank" rel="noopener nofollow" href="https://github.com/lepoco/Genius"><small>https://github.com/lepoco/Genius</small></a>
              </p>
              <hr/>

              {/* @foreach($softwareList as $software)
              <p className="-reveal">
                {{ $software['name'] ?? '' }}
                <br>
                <small>{{ $software['license'] ?? '' }} -</small> <a target="_blank" rel="noopener nofollow"
                  href="{{ $software['url'] ?? '' }}"><small>{{ $software['url'] ?? '' }}</small></a>
              </p>
              @endforeach */}

            </div>
          </div>
        </div>
      </div>
    );
  }
}

