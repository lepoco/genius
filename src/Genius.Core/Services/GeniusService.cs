// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Genius.Core.Data.Contexts;
using Genius.Core.Expert.Interfaces;

namespace Genius.Core.Services;

public class GeniusService : IExpertService
{
    /// <summary>
    /// Scoped list of used solvers.
    /// </summary>
    private readonly List<ISolver> _solvers = new();

    public IExpertContext Context { get; }

    public GeniusService(IExpertContext expertContext)
    {
        Context = expertContext;
    }

    public async Task<ISolverResponse> Solve<T>(ISolverQuestion question)
    {
        return await (GetSolver<T>() as ISolver)!.Solve(question);
    }

    public T GetSolver<T>()
    {
        if (!typeof(ISolver).IsAssignableFrom(typeof(T)))
            throw new InvalidCastException();

        if (!_solvers.OfType<T>().Any())
        {
            var solverObject = (T)Activator.CreateInstance(typeof(T)) as ISolver;

            if (solverObject == null)
                throw new ArgumentNullException($"Creating new solver of type {typeof(T)} failed.");

            solverObject.SetContext(Context);

            _solvers.Add(solverObject);
        }

        var solverInstance = _solvers.First(solver => solver.GetType() == typeof(T));

        return (T)solverInstance;
    }
}
