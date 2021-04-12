// This Source Code Form is subject to the terms of the GNU General Public License, Version 3.
// If a copy of the GPL was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.txt.
// Copyright (C) 2021 Leszek Pomianowski
// All Rights Reserved.

using System;
using System.Collections.Generic;

namespace Genius.Code.Engine.Predictor
{
    /// <summary>
    /// Expert system engine looking for matching solutions.
    /// </summary>
    public sealed class Solver
    {
        private Engine.System _localExpertSystem;
        private Dictionary<int, Predictor.Response> _responses;

        public Solver()
        {

        }

        /// <summary>
        /// Loads into the class memory an instance of the expert system on which operations will be performed.
        /// </summary>
        /// <param name="system">Expert system class instance containing <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s.</param>
        public void LoadExpertSystem(Engine.System system)
        {
            this._localExpertSystem = system;
        }

        /// <summary>
        /// Collects identifiers of <see cref="Engine.Condition"/>'s and <see cref="Engine.Product"/>'s from which operations will be performed.
        /// </summary>
        public void BuildPredictionDictionary()
        {
            if (this._localExpertSystem == null)
                throw new InvalidOperationException("Expert System was not loaded, unable to create Prediction Dictionary. Use the LoadExpertSystem().");
        }
    }
}
